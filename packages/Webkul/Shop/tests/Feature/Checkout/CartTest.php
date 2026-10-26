<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\TestResponse;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\Product;
use Webkul\Sales\Models\Order;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\delete;
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

it('should start an isolated buy now cart and mirror the item into the basket', function () {
    $first = $this->createSimpleProduct();

    $second = $this->createSimpleProduct();

    $basketCartId = $this->addProductToCart($first->id)->json('data.id');

    $response = $this->addProductToCart($second->id, 1, ['is_buy_now' => 1])->assertOk();

    $buyNowCartId = $response->json('data.id');

    $response->assertJsonPath('data.items_count', 1);

    parse_str((string) parse_url($response->json('redirect'), PHP_URL_QUERY), $redirectQuery);

    expect($response->json('redirect'))->toStartWith(route('shop.checkout.onepage.index'))
        ->and($redirectQuery['buy_now'] ?? null)->not->toBe((string) $buyNowCartId)
        ->and($this->parseBuyNowToken($redirectQuery['buy_now'] ?? null))->toBe($buyNowCartId);

    expect($buyNowCartId)->not->toBe($basketCartId);

    $this->assertDatabaseHas('cart', ['id' => $buyNowCartId, 'is_buy_now' => 1, 'is_active' => 0]);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $buyNowCartId, 'product_id' => $second->id]);

    $this->assertDatabaseMissing('cart_items', ['cart_id' => $buyNowCartId, 'product_id' => $first->id]);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $basketCartId, 'product_id' => $second->id]);

    getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.id', $basketCartId)
        ->assertJsonPath('data.items_count', 2);
});

it('should hand the basket back when a buy now add fails', function () {
    $basketProduct = $this->createSimpleProduct();

    $outOfStock = $this->setProductStock($this->createSimpleProduct(), 0);

    $basketCartId = $this->addProductToCart($basketProduct->id)->json('data.id');

    $this->addProductToCart($outOfStock->id, 1, ['is_buy_now' => 1])
        ->assertBadRequest()
        ->assertJsonPath('message', trans('product::app.checkout.cart.inventory-warning'));

    $this->assertDatabaseHas('cart', ['id' => $basketCartId, 'is_active' => 1]);

    $this->assertDatabaseMissing('cart_items', ['product_id' => $outOfStock->id]);

    getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.id', $basketCartId)
        ->assertJsonPath('data.items_count', 1);
});

it('should keep every buy now cart independent of the others', function () {
    $first = $this->createSimpleProduct();

    $second = $this->createSimpleProduct();

    $third = $this->createSimpleProduct();

    $basketCartId = $this->addProductToCart($first->id)->json('data.id');

    $buyNowOne = $this->addProductToCart($second->id, 1, ['is_buy_now' => 1])->json('data.id');

    $buyNowTwo = $this->addProductToCart($third->id, 1, ['is_buy_now' => 1])->json('data.id');

    expect($buyNowOne)->not->toBe($buyNowTwo);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $buyNowOne, 'product_id' => $second->id]);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $buyNowTwo, 'product_id' => $third->id]);

    $this->assertDatabaseMissing('cart_items', ['cart_id' => $buyNowOne, 'product_id' => $third->id]);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $basketCartId, 'product_id' => $second->id]);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $basketCartId, 'product_id' => $third->id]);

    getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.id', $basketCartId)
        ->assertJsonPath('data.items_count', 3);
});

// ============================================================================
// Buy Now Runs On Its Own Cart
// ============================================================================

it('should place a buy now order on its own cart and leave the basket', function () {
    $first = $this->createSimpleProduct();

    $second = $this->createSimpleProduct();

    $basketCartId = $this->addProductToCart($first->id)->json('data.id');

    $buyNowCartId = $this->addProductToCart($second->id, 1, ['is_buy_now' => 1])->json('data.id');

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'buy_now' => $this->buyNowToken($buyNowCartId),
        'billing' => array_merge($this->storefrontAddress(), ['use_for_shipping' => true]),
        'shipping' => $this->storefrontAddress(),
    ])->assertOk();

    postJson(route('shop.checkout.onepage.shipping_methods.store'), [
        'buy_now' => $this->buyNowToken($buyNowCartId),
        'shipping_method' => 'free_free',
    ])->assertOk();

    postJson(route('shop.checkout.onepage.payment_methods.store'), [
        'buy_now' => $this->buyNowToken($buyNowCartId),
        'payment' => ['method' => 'cashondelivery'],
    ])->assertOk();

    $this->assertOrderPlaced(postJson(route('shop.checkout.onepage.orders.store'), ['buy_now' => $this->buyNowToken($buyNowCartId)]));

    $this->assertDatabaseHas('order_items', ['product_id' => $second->id]);

    $this->assertDatabaseMissing('order_items', ['product_id' => $first->id]);

    $this->forgetResolvedCart();

    getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.id', $basketCartId)
        ->assertJsonPath('data.items_count', 1);

    $this->assertDatabaseHas('cart', ['id' => $basketCartId, 'is_active' => 1]);
});

it('should not place a second order when a completed buy now checkout is replayed', function () {
    $this->addProductToCart($this->createSimpleProduct()->id);

    $buyNowCartId = $this->addProductToCart($this->createSimpleProduct()->id, 1, ['is_buy_now' => 1])->json('data.id');

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'buy_now' => $this->buyNowToken($buyNowCartId),
        'billing' => array_merge($this->storefrontAddress(), ['use_for_shipping' => true]),
        'shipping' => $this->storefrontAddress(),
    ])->assertOk();

    postJson(route('shop.checkout.onepage.shipping_methods.store'), [
        'buy_now' => $this->buyNowToken($buyNowCartId),
        'shipping_method' => 'free_free',
    ])->assertOk();

    postJson(route('shop.checkout.onepage.payment_methods.store'), [
        'buy_now' => $this->buyNowToken($buyNowCartId),
        'payment' => ['method' => 'cashondelivery'],
    ])->assertOk();

    $this->assertOrderPlaced(postJson(route('shop.checkout.onepage.orders.store'), ['buy_now' => $this->buyNowToken($buyNowCartId)]));

    postJson(route('shop.checkout.onepage.orders.store'), ['buy_now' => $this->buyNowToken($buyNowCartId)])->assertOk();

    expect(Order::query()->where('cart_id', $buyNowCartId)->count())->toBe(1);
});

it('should show the buy now cart on the checkout summary while the basket stays its own', function () {
    $first = $this->createSimpleProduct();

    $second = $this->createSimpleProduct();

    $basketCartId = $this->addProductToCart($first->id)->json('data.id');

    $buyNowCartId = $this->addProductToCart($second->id, 1, ['is_buy_now' => 1])->json('data.id');

    getJson(route('shop.checkout.onepage.summary', ['buy_now' => $this->buyNowToken($buyNowCartId)]))
        ->assertOk()
        ->assertJsonPath('data.id', $buyNowCartId)
        ->assertJsonPath('data.items_count', 1);

    $this->forgetResolvedCart();

    getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.id', $basketCartId)
        ->assertJsonPath('data.items_count', 2);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $basketCartId, 'product_id' => $second->id]);

    $this->assertDatabaseHas('cart', ['id' => $basketCartId, 'is_active' => 1]);
});

it('should drop a coupon the merged cart no longer qualifies for', function () {
    $first = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    $second = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    $this->createCouponCartRule(
        'GUESTONLY',
        ['action_type' => 'by_fixed', 'discount_amount' => 50],
        [1]
    );

    $this->addProductToCart($first->id);

    $this->applyCoupon('GUESTONLY')->assertOk();

    $customer = Customer::factory()->create(['password' => Hash::make('secret-password')]);

    Cart::factory()->create([
        'customer_id' => $customer->id,
        'is_guest' => 0,
        'is_active' => 1,
    ]);

    $this->addProductToCart($second->id);

    post(route('shop.customer.session.create'), [
        'email' => $customer->email,
        'password' => 'secret-password',
    ])->assertRedirect();

    $response = getJson(route('shop.api.checkout.cart.index'))->assertOk();

    expect($response->json('data.coupon_code'))->toBeNull();

    $this->assertCartDiscount($response, 0);
});

it('should carry the basket and the buy now cart to a guest who signs in', function () {
    $customer = Customer::factory()->create(['password' => Hash::make('secret-password')]);

    $first = $this->createSimpleProduct();

    $second = $this->createSimpleProduct();

    $basketCartId = $this->addProductToCart($first->id)->json('data.id');

    $buyNowCartId = $this->addProductToCart($second->id, 1, ['is_buy_now' => 1])->json('data.id');

    post(route('shop.customer.session.create'), [
        'email' => $customer->email,
        'password' => 'secret-password',
    ])->assertRedirect();

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'buy_now' => $this->buyNowToken($buyNowCartId),
        'billing' => array_merge($this->storefrontAddress(), ['use_for_shipping' => true]),
        'shipping' => $this->storefrontAddress(),
    ])->assertOk();

    postJson(route('shop.checkout.onepage.shipping_methods.store'), [
        'buy_now' => $this->buyNowToken($buyNowCartId),
        'shipping_method' => 'free_free',
    ])->assertOk();

    postJson(route('shop.checkout.onepage.payment_methods.store'), [
        'buy_now' => $this->buyNowToken($buyNowCartId),
        'payment' => ['method' => 'cashondelivery'],
    ])->assertOk();

    $this->assertOrderPlaced(postJson(route('shop.checkout.onepage.orders.store'), ['buy_now' => $this->buyNowToken($buyNowCartId)]));

    $this->assertDatabaseHas('order_items', ['product_id' => $second->id]);

    $this->assertDatabaseMissing('order_items', ['product_id' => $first->id]);

    $this->forgetResolvedCart();

    getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.id', $basketCartId)
        ->assertJsonPath('data.items_count', 1);

    $this->assertDatabaseHas('cart', [
        'id' => $basketCartId,
        'is_active' => 1,
        'customer_id' => $customer->id,
    ]);

    $this->assertDatabaseHas('cart', [
        'id' => $buyNowCartId,
        'is_buy_now' => 1,
        'customer_id' => $customer->id,
    ]);
});

it('should keep an unfinished buy now in the basket across a logout and a login', function () {
    $customer = Customer::factory()->create(['password' => Hash::make('secret-password')]);

    $login = fn () => post(route('shop.customer.session.create'), [
        'email' => $customer->email,
        'password' => 'secret-password',
    ])->assertRedirect();

    $login();

    $first = $this->createSimpleProduct();

    $second = $this->createSimpleProduct();

    $basketCartId = $this->addProductToCart($first->id)->json('data.id');

    $buyNowCartId = $this->addProductToCart($second->id, 1, ['is_buy_now' => 1])->json('data.id');

    delete(route('shop.customer.session.destroy'))->assertRedirect();

    $login();

    getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.id', $basketCartId)
        ->assertJsonPath('data.items_count', 2);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $basketCartId, 'product_id' => $first->id]);

    $this->assertDatabaseHas('cart_items', ['cart_id' => $basketCartId, 'product_id' => $second->id]);

    $this->assertDatabaseHas('cart', ['id' => $buyNowCartId, 'is_buy_now' => 1]);
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

it('should take the cart away with the last item removed from it', function () {
    $product = $this->createSimpleProduct();

    $cartItemId = $this->addProductToCart($product->id)->json('data.items.0.id');

    $cartId = CartItem::query()->findOrFail($cartItemId)->cart_id;

    $this->removeCartItem($cartItemId)
        ->assertOk()
        ->assertJsonPath('data', null);

    $this->assertDatabaseMissing('cart_items', ['id' => $cartItemId]);

    $this->assertDatabaseMissing('cart', ['id' => $cartId]);
});

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

it('should reject a selection that names the same item twice', function () {
    $product = $this->createSimpleProduct();

    $itemId = $this->addProductToCart($product->id)->json('data.items.0.id');

    deleteJson(route('shop.api.checkout.cart.destroy_selected'), ['ids' => [$itemId, $itemId]])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('ids.0');

    $this->assertDatabaseHas('cart_items', ['id' => $itemId]);
});

it('should reject a selection that names no item to remove', function (mixed $ids) {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    deleteJson(route('shop.api.checkout.cart.destroy_selected'), $ids)
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('ids');
})->with([
    'no ids at all' => [[]],
    'an empty selection' => [['ids' => []]],
    'a selection that is not a list' => [['ids' => 'all']],
]);

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

it('should take the cart away with the last item moved out of it', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $cartItemId = $this->addProductToCart($product->id)->json('data.items.0.id');

    $cartId = CartItem::query()->findOrFail($cartItemId)->cart_id;

    postJson(route('shop.api.checkout.cart.move_to_wishlist'), [
        'ids' => [$cartItemId],
        'qty' => [1],
    ])
        ->assertOk()
        ->assertJsonPath('data', null);

    $this->assertDatabaseMissing('cart', ['id' => $cartId]);
});

it('should keep the cart and its other items when one of several is moved out', function () {
    $staying = $this->createSimpleProduct();

    $moving = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($staying->id);

    $cartItemId = $this->addProductToCart($moving->id)->json('data.items.0.id');

    $cartId = CartItem::query()->findOrFail($cartItemId)->cart_id;

    postJson(route('shop.api.checkout.cart.move_to_wishlist'), [
        'ids' => [$cartItemId],
        'qty' => [1],
    ])->assertOk();

    $this->assertDatabaseHas('cart', ['id' => $cartId]);

    expect(CartItem::query()->where('cart_id', $cartId)->count())->toBe(1);
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

it('should refuse a coupon that exists while its cart rule is switched off', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    $this->createCouponCartRule('SWITCHED-OFF', ['status' => 0]);

    $this->addProductToCart($product->id);

    $this->applyCoupon('SWITCHED-OFF')
        ->assertUnprocessable()
        ->assertJsonPath('message', trans('shop::app.checkout.coupon.apply-issue'))
        ->assertJsonPath('data.coupon_code', null);
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

it('should keep the shipping it quoted on the estimate instead of dropping it on the next collect', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    $estimate = postJson(route('shop.api.checkout.cart.estimate_shipping'), [
        'country' => 'US',
        'state' => 'CA',
        'postcode' => '90001',
        'shipping_method' => 'flatrate_flatrate',
    ])
        ->assertOk()
        ->assertJsonPath('data.cart.shipping_method', 'flatrate_flatrate');

    $this->assertDatabaseHas('cart_shipping_rates', [
        'cart_id' => $estimate->json('data.cart.id'),
        'method' => 'flatrate_flatrate',
    ]);

    getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.shipping_method', 'flatrate_flatrate')
        ->assertJsonPath('data.shipping_amount', $estimate->json('data.cart.shipping_amount'))
        ->assertJsonPath('data.grand_total', $estimate->json('data.cart.grand_total'));
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
