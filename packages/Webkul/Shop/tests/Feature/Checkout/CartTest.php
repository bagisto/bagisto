<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\TestResponse;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\Product;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

/**
 * Create a simple product at the given price, taxed at the given rate for shoppers in the given country.
 */
function taxedProduct(float $price, float $rate, string $country): Product
{
    $taxRate = TaxRate::factory()->create([
        'country' => $country,
        'state' => '',
        'zip_code' => '*',
        'tax_rate' => $rate,
    ]);

    $taxCategory = TaxCategory::factory()->create();

    TaxMap::factory()->create([
        'tax_category_id' => $taxCategory->id,
        'tax_rate_id' => $taxRate->id,
    ]);

    return test()->createSimpleProduct([
        'price' => ['float_value' => $price],
        'tax_category_id' => ['integer_value' => $taxCategory->id, 'channel' => core()->getCurrentChannelCode()],
    ]);
}

/**
 * Put a product taxed at 10% for India in a customer's cart shipping to India, apply a 10% coupon
 * under the given tax settings, and return the cart the coupon left behind.
 */
function taxedCartWithCoupon(float $price, string $productPrices, string $applyTaxOn): TestResponse
{
    test()->setConfig([
        'sales.taxes.calculation.based_on' => 'shipping_address',
        'sales.taxes.calculation.product_prices' => $productPrices,
        'sales.taxes.calculation.apply_tax_on' => $applyTaxOn,
    ]);

    $product = taxedProduct($price, 10, 'IN');

    test()->createCouponCartRule('TAXTEST10', ['action_type' => 'by_percent', 'discount_amount' => 10]);

    test()->loginAsCustomer();

    test()->addProductToCart($product->id);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => test()->storefrontAddress([
            'country' => 'IN',
            'state' => 'DL',
            'postcode' => '110001',
            'use_for_shipping' => true,
        ]),
    ])->assertOk();

    return test()->applyCoupon('TAXTEST10')->assertOk();
}

// ============================================================================
// Cart Display
// ============================================================================

it('should display the cart with its totals', function (bool $signedIn) {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    if ($signedIn) {
        $this->loginAsCustomer();
    }

    $this->addProductToCart($product->id, 2)->assertOk();

    $response = getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 2)
        ->assertJsonPath('data.is_guest', ! $signedIn);

    expect((float) $response->json('data.sub_total'))->toBePrice(200)
        ->and((float) $response->json('data.grand_total'))->toBePrice(200);
})->with('shoppers');

it('should answer with no cart when nothing has been added yet', function () {
    getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data', null);
});

// ============================================================================
// Add To Cart
// ============================================================================

it('should fail validation when product id is not provided on add to cart', function () {
    postJson(route('shop.api.checkout.cart.store'), ['quantity' => 1])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('product_id');
});

it('should add a simple product to the cart', function (bool $signedIn) {
    $product = $this->createSimpleProduct();

    if ($signedIn) {
        $this->loginAsCustomer();
    }

    $this->addProductToCart($product->id)
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.type', 'simple')
        ->assertJsonPath('data.is_guest', ! $signedIn);

    $this->assertCartHasProduct($product->id);
})->with('shoppers');

it('should add a virtual product to the cart', function (bool $signedIn) {
    $product = $this->createVirtualProduct();

    if ($signedIn) {
        $this->loginAsCustomer();
    }

    $this->addProductToCart($product->id)
        ->assertOk()
        ->assertJsonPath('data.items.0.type', 'virtual')
        ->assertJsonPath('data.have_stockable_items', false);
})->with('shoppers');

it('should merge a product added twice into a single cart line', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id, 1)->assertOk();

    $this->addProductToCart($product->id, 2)
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 3);

    $this->assertCartHasProduct($product->id, 3);
});

it('should refuse to add an inactive product to the cart', function () {
    $product = $this->createSimpleProduct([
        'status' => ['boolean_value' => false, 'channel' => core()->getCurrentChannelCode()],
    ]);

    $this->addProductToCart($product->id)
        ->assertBadRequest()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.inactive-add'));

    $this->assertDatabaseMissing('cart_items', ['product_id' => $product->id]);
});

it('should refuse to add more of a product than is in stock', function () {
    $product = $this->setProductStock($this->createSimpleProduct(), 2);

    $this->addProductToCart($product->id, 5)
        ->assertBadRequest()
        ->assertJsonPath('message', trans('product::app.checkout.cart.inventory-warning'));

    $this->assertDatabaseMissing('cart_items', ['product_id' => $product->id]);
});

it('should start a fresh cart for a buy now purchase and set the current one aside', function () {
    $first = $this->createSimpleProduct();

    $second = $this->createSimpleProduct();

    $firstCartId = $this->addProductToCart($first->id)->json('data.id');

    $buyNowCartId = $this->addProductToCart($second->id, 1, ['is_buy_now' => 1])
        ->assertOk()
        ->assertJsonPath('redirect', route('shop.checkout.onepage.index'))
        ->assertJsonPath('data.items_count', 1)
        ->json('data.id');

    expect($buyNowCartId)->not->toBe($firstCartId);

    $this->assertDatabaseHas('cart', ['id' => $firstCartId, 'is_active' => 0]);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $buyNowCartId, 'product_id' => $second->id]);

    $this->assertDatabaseMissing('cart_items', ['cart_id' => $buyNowCartId, 'product_id' => $first->id]);
});

// ============================================================================
// Update Quantities
// ============================================================================

it('should update the quantity of a cart item', function (bool $signedIn) {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    if ($signedIn) {
        $this->loginAsCustomer();
    }

    $cartItemId = $this->addProductToCart($product->id)->json('data.items.0.id');

    $response = $this->updateCartItem($cartItemId, 3)
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.index.quantity-update'))
        ->assertJsonPath('data.items_qty', 3);

    expect((float) $response->json('data.grand_total'))->toBePrice(300);

    $this->assertDatabaseHas('cart_items', ['id' => $cartItemId, 'quantity' => 3]);
})->with('shoppers');

it('should remove the cart item when its quantity is set to zero', function () {
    $product = $this->createSimpleProduct();

    $cartItemId = $this->addProductToCart($product->id)->json('data.items.0.id');

    $this->updateCartItem($cartItemId, 0)
        ->assertOk()
        ->assertJsonPath('data.message', trans('shop::app.checkout.cart.illegal'));

    $this->assertDatabaseMissing('cart_items', ['id' => $cartItemId]);
});

it('should refuse a quantity beyond the stock and keep the current one', function () {
    $product = $this->setProductStock($this->createSimpleProduct(), 3);

    $cartItemId = $this->addProductToCart($product->id)->json('data.items.0.id');

    $this->updateCartItem($cartItemId, 5)
        ->assertOk()
        ->assertJsonPath('data.message', trans('shop::app.checkout.cart.inventory-warning'));

    $this->assertDatabaseHas('cart_items', ['id' => $cartItemId, 'quantity' => 1]);
});

it('should ignore a quantity update aimed at an item of another cart', function () {
    $product = $this->createSimpleProduct();

    $otherCart = Cart::factory()->create();

    $otherItem = CartItem::factory()->adjustProduct()->create([
        'cart_id' => $otherCart->id,
        'product_id' => $product->id,
        'sku' => $product->sku,
        'name' => $product->name,
        'type' => 'simple',
        'quantity' => 1,
    ]);

    $this->addProductToCart($product->id);

    $this->updateCartItem($otherItem->id, 5)->assertOk();

    $this->assertDatabaseHas('cart_items', ['id' => $otherItem->id, 'quantity' => 1]);
});

// ============================================================================
// Remove Items
// ============================================================================

it('should fail validation when the cart item id is missing or unknown on remove', function (?int $cartItemId) {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    deleteJson(route('shop.api.checkout.cart.destroy'), $cartItemId ? ['cart_item_id' => $cartItemId] : [])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('cart_item_id');
})->with([
    'missing id' => [null],
    'unknown id' => [999999999],
]);

it('should remove a product from the cart', function (bool $signedIn) {
    $product = $this->createSimpleProduct();

    if ($signedIn) {
        $this->loginAsCustomer();
    }

    $cartItemId = $this->addProductToCart($product->id)->json('data.items.0.id');

    $this->removeCartItem($cartItemId)
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.success-remove'));

    $this->assertDatabaseMissing('cart_items', ['id' => $cartItemId]);
})->with('shoppers');

it('should remove only the chosen product when the cart holds two', function () {
    $kept = $this->createSimpleProduct();

    $removed = $this->createSimpleProduct();

    $this->addProductToCart($kept->id);

    $cartId = $this->addProductToCart($removed->id)->json('data.id');

    $removedItem = CartItem::query()->where('cart_id', $cartId)->where('product_id', $removed->id)->firstOrFail();

    $this->removeCartItem($removedItem->id)
        ->assertOk()
        ->assertJsonPath('data.items_count', 1);

    $this->assertDatabaseMissing('cart_items', ['id' => $removedItem->id]);

    $this->assertCartHasProduct($kept->id);
});

it('should remove the selected products from the cart', function (bool $signedIn) {
    $first = $this->createSimpleProduct();

    $second = $this->createSimpleProduct();

    if ($signedIn) {
        $this->loginAsCustomer();
    }

    $this->addProductToCart($first->id);

    $response = $this->addProductToCart($second->id);

    $itemIds = collect($response->json('data.items'))->pluck('id')->all();

    deleteJson(route('shop.api.checkout.cart.destroy_selected'), ['ids' => $itemIds])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.index.remove-selected-success'));

    foreach ($itemIds as $itemId) {
        $this->assertDatabaseMissing('cart_items', ['id' => $itemId]);
    }
})->with('shoppers');

// ============================================================================
// Move To Wishlist
// ============================================================================

it('should move a cart item to the wishlist of the customer', function () {
    $product = $this->createSimpleProduct();

    $customer = $this->loginAsCustomer();

    $cartItemId = $this->addProductToCart($product->id)->json('data.items.0.id');

    postJson(route('shop.api.checkout.cart.move_to_wishlist'), [
        'ids' => [$cartItemId],
        'qty' => [1],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.index.move-to-wishlist-success'));

    $this->assertDatabaseHas('wishlist_items', [
        'customer_id' => $customer->id,
        'product_id' => $product->id,
    ]);

    $this->assertDatabaseMissing('cart_items', ['id' => $cartItemId]);
});

// ============================================================================
// Coupons
// ============================================================================

it('should refuse a coupon code that does not exist', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    $this->applyCoupon('NO-SUCH-COUPON')
        ->assertUnprocessable()
        ->assertJsonPath('message', trans('shop::app.checkout.coupon.invalid'))
        ->assertJsonPath('data.coupon_code', null);
});

it('should refuse a coupon that is already applied to the cart', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    $this->createCouponCartRule('ONCE', ['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $this->addProductToCart($product->id);

    $this->applyCoupon('ONCE')->assertOk();

    $this->applyCoupon('ONCE')
        ->assertUnprocessable()
        ->assertJsonPath('message', trans('shop::app.checkout.coupon.already-applied'));
});

it('should remove an applied coupon and its discount from the cart', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    $this->createCouponCartRule('DROPME', ['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $this->addProductToCart($product->id);

    $this->assertCartDiscount($this->applyCoupon('DROPME')->assertOk(), 50);

    $response = deleteJson(route('shop.api.checkout.cart.coupon.remove'))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.coupon.remove'))
        ->assertJsonPath('data.coupon_code', null);

    $this->assertCartDiscount($response, 0);

    $this->assertCartGrandTotal(500);
});

// ============================================================================
// Cart Merge On Login
// ============================================================================

it('should hand the guest cart to the customer who signs in without a cart of their own', function () {
    $product = $this->createSimpleProduct();

    $cartId = $this->addProductToCart($product->id)->json('data.id');

    $customer = Customer::factory()->create(['password' => Hash::make('secret-password')]);

    post(route('shop.customer.session.create'), [
        'email' => $customer->email,
        'password' => 'secret-password',
    ])->assertRedirect();

    $this->assertDatabaseHas('cart', [
        'id' => $cartId,
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'is_guest' => false,
    ]);

    $this->assertCartHasProduct($product->id);
});

it('should merge the guest cart into the cart the customer already has', function () {
    $ownProduct = $this->createSimpleProduct();

    $guestProduct = $this->createSimpleProduct();

    $customer = Customer::factory()->create(['password' => Hash::make('secret-password')]);

    $customerCart = Cart::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'is_guest' => false,
        'is_active' => 1,
    ]);

    CartItem::factory()->adjustProduct()->create([
        'cart_id' => $customerCart->id,
        'product_id' => $ownProduct->id,
        'sku' => $ownProduct->sku,
        'name' => $ownProduct->name,
        'type' => 'simple',
        'quantity' => 1,
        'additional' => ['product_id' => $ownProduct->id, 'quantity' => 1],
    ]);

    $guestCartId = $this->addProductToCart($guestProduct->id)->json('data.id');

    post(route('shop.customer.session.create'), [
        'email' => $customer->email,
        'password' => 'secret-password',
    ])->assertRedirect();

    foreach ([$ownProduct, $guestProduct] as $product) {
        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $customerCart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }

    $this->assertDatabaseMissing('cart', ['id' => $guestCartId]);
});

// ============================================================================
// Cross Sells And Shipping Estimate
// ============================================================================

it('should offer the cross-sell products of the items in the cart', function () {
    $product = $this->createSimpleProduct();

    $crossSell = $this->createSimpleProduct();

    $product->cross_sells()->attach($crossSell->id);

    $this->addProductToCart($product->id);

    getJson(route('shop.api.checkout.cart.cross_sell.index'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $crossSell->id);
});

it('should offer nothing to cross-sell without a cart', function () {
    getJson(route('shop.api.checkout.cart.cross_sell.index'))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

it('should estimate the shipping methods for a destination and apply the chosen one', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    $response = postJson(route('shop.api.checkout.cart.estimate_shipping'), [
        'country' => 'US',
        'state' => 'CA',
        'postcode' => '90001',
        'shipping_method' => 'free_free',
    ])
        ->assertOk()
        ->assertJsonPath('data.cart.shipping_method', 'free_free');

    $methods = collect($response->json('data.shipping_methods'))->pluck('rates.*.method')->flatten();

    expect($methods)->toContain('free_free');
});

it('should fail validation when the shipping estimate has no destination', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    postJson(route('shop.api.checkout.cart.estimate_shipping'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('country')
        ->assertJsonValidationErrorFor('state')
        ->assertJsonValidationErrorFor('postcode');
});

// ============================================================================
// Tax
// ============================================================================

it('should add the tax of the default destination to a product in the cart', function () {
    $this->setConfig('sales.taxes.default_destination_calculation.country', 'US');

    $product = taxedProduct(1000, 10, 'US');

    $response = $this->addProductToCart($product->id)->assertOk();

    expect((float) $response->json('data.tax_total'))->toBePrice(100)
        ->and((float) $response->json('data.items.0.price_incl_tax'))->toBePrice(1100)
        ->and((float) $response->json('data.grand_total'))->toBePrice(1100);
});

it('should calculate tax on the discounted price when tax is applied after the discount', function () {
    $response = taxedCartWithCoupon(1000, 'excluding_tax', 'after_discount');

    $this->assertCartDiscount($response, 100);

    expect((float) $response->json('data.tax_total'))->toBePrice(90)
        ->and((float) $response->json('data.grand_total'))->toBePrice(990);
});

it('should calculate tax on the full price when tax is applied before the discount', function () {
    $response = taxedCartWithCoupon(1000, 'excluding_tax', 'before_discount');

    $this->assertCartDiscount($response, 100);

    expect((float) $response->json('data.tax_total'))->toBePrice(100)
        ->and((float) $response->json('data.grand_total'))->toBePrice(1000);
});

it('should keep the tax inside an inclusive price whichever way the discount is applied', function () {
    $response = taxedCartWithCoupon(1100, 'including_tax', 'after_discount');

    $subTotalInclTax = (float) $response->json('data.sub_total_incl_tax');

    $discount = (float) $response->json('data.discount_amount');

    expect($discount)->toBeGreaterThan(0)
        ->and((float) $response->json('data.tax_total'))->toBePrice($subTotalInclTax * 10 / 110)
        ->and((float) $response->json('data.grand_total'))->toBePrice($subTotalInclTax - $discount);
});
