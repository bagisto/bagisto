<?php

use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Core\Models\CoreConfig;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

// ============================================================================
// Cart Display
// ============================================================================

it('should display the cart items for a guest user', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id)->assertOk();

    get(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.is_guest', true);
});

it('should display the cart items for a customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($product->id)->assertOk();

    get(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.is_guest', false);
});

// ============================================================================
// Remove Cart Item — Validation
// ============================================================================

it('should fail validation when cart item id is not provided on remove for guest', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    deleteJson(route('shop.api.checkout.cart.destroy'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('cart_item_id');
});

it('should fail validation when cart item id is not provided on remove for customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($product->id);

    deleteJson(route('shop.api.checkout.cart.destroy'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('cart_item_id');
});

it('should fail validation when wrong cart item id is provided on remove for guest', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    deleteJson(route('shop.api.checkout.cart.destroy'), ['cart_item_id' => 99999])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('cart_item_id');
});

// ============================================================================
// Remove Cart Item
// ============================================================================

it('should remove a product from the guest cart', function () {
    $product = $this->createSimpleProduct();

    $response = $this->addProductToCart($product->id);
    $cartItemId = $response->json('data.items.0.id');

    $this->removeCartItem($cartItemId)->assertOk();

    $this->assertDatabaseMissing('cart_items', ['id' => $cartItemId]);
});

it('should remove a product from the customer cart', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $response = $this->addProductToCart($product->id);
    $cartItemId = $response->json('data.items.0.id');

    $this->removeCartItem($cartItemId)->assertOk();

    $this->assertDatabaseMissing('cart_items', ['id' => $cartItemId]);
});

it('should remove only one product when cart contains two products for guest', function () {
    $productA = $this->createSimpleProduct();
    $productB = $this->createSimpleProduct();

    $this->addProductToCart($productA->id);
    $responseB = $this->addProductToCart($productB->id);

    $cartItemBId = $responseB->json('data.items.1.id') ?? $responseB->json('data.items.0.id');

    $this->removeCartItem($cartItemBId)->assertOk();

    $this->assertDatabaseHas('cart_items', ['product_id' => $productA->id]);
});

// ============================================================================
// Remove All Cart Items
// ============================================================================

it('should remove selected products from the guest cart', function () {
    $productA = $this->createSimpleProduct();
    $productB = $this->createSimpleProduct();

    $this->addProductToCart($productA->id);
    $response = $this->addProductToCart($productB->id);

    $itemIds = collect($response->json('data.items'))->pluck('id')->toArray();

    deleteJson(route('shop.api.checkout.cart.destroy_selected'), [
        'ids' => $itemIds,
    ])
        ->assertOk();
});

it('should remove selected products from the customer cart', function () {
    $productA = $this->createSimpleProduct();
    $productB = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($productA->id);
    $response = $this->addProductToCart($productB->id);

    $itemIds = collect($response->json('data.items'))->pluck('id')->toArray();

    deleteJson(route('shop.api.checkout.cart.destroy_selected'), [
        'ids' => $itemIds,
    ])
        ->assertOk();
});

// ============================================================================
// Update Cart Quantities
// ============================================================================

it('should update cart quantities for a guest user', function () {
    $product = $this->createSimpleProduct();

    $response = $this->addProductToCart($product->id);
    $cartItemId = $response->json('data.items.0.id');

    $this->updateCartItem($cartItemId, 3)->assertOk();

    $this->assertDatabaseHas('cart_items', ['id' => $cartItemId, 'quantity' => 3]);
});

it('should update cart quantities for a customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $response = $this->addProductToCart($product->id);
    $cartItemId = $response->json('data.items.0.id');

    $this->updateCartItem($cartItemId, 5)->assertOk();

    $this->assertDatabaseHas('cart_items', ['id' => $cartItemId, 'quantity' => 5]);
});

// ============================================================================
// Add Simple Product
// ============================================================================

it('should fail validation when product id is not provided on add to cart', function () {
    postJson(route('shop.api.checkout.cart.store'), ['quantity' => 1])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('product_id');
});

it('should add a simple product to the cart for a guest user', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id)
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.type', 'simple');
});

it('should add a simple product to the cart for a customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($product->id)
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.is_guest', false);
});

// ============================================================================
// Add Virtual Product
// ============================================================================

it('should add a virtual product to the cart for a guest user', function () {
    $product = $this->createVirtualProduct();

    $this->addProductToCart($product->id)
        ->assertOk()
        ->assertJsonPath('data.items.0.type', 'virtual');
});

it('should add a virtual product to the cart for a customer', function () {
    $product = $this->createVirtualProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($product->id)
        ->assertOk()
        ->assertJsonPath('data.items.0.type', 'virtual');
});

// ============================================================================
// Tax Calculation
// ============================================================================

it('should calculate including tax when adding a product to the cart', function () {
    $taxRate = TaxRate::factory()->create(['tax_rate' => 10]);

    $taxCategory = TaxCategory::factory()->create();
    TaxMap::factory()->create([
        'tax_category_id' => $taxCategory->id,
        'tax_rate_id' => $taxRate->id,
    ]);

    $product = $this->createSimpleProduct([
        'tax_category_id' => ['integer_value' => $taxCategory->id, 'channel' => core()->getCurrentChannelCode()],
    ]);

    $this->addProductToCart($product->id)
        ->assertOk()
        ->assertJsonPath('data.items_count', 1);
});

it('should calculate tax on the discounted price when apply tax on is set to after discount', function () {
    // Arrange.
    $taxCategory = TaxCategory::factory()->create();

    $taxRate = TaxRate::factory()->create([
        'zip_code' => '',
        'state' => '',
        'country' => $countryCode = 'IN',
        'tax_rate' => 10,
    ]);

    TaxMap::factory()->create([
        'tax_category_id' => $taxCategory->id,
        'tax_rate_id' => $taxRate->id,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            4 => 'tax_category_id',
            5 => 'new',
            6 => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'tax_category_id' => [
                'integer_value' => $taxCategory->id,
            ],
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => 1000,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_percent',
        'discount_amount' => 10,
        'usage_per_customer' => 0,
        'uses_per_coupon' => 0,
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 0,
        'use_auto_generation' => 0,
        'times_used' => 0,
        'coupon_type' => 1,
        'end_other_rules' => 0,
        'uses_attribute_conditions' => 0,
        'discount_step' => 0,
        'free_shipping' => 0,
        'sort_order' => 0,
        'conditions' => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from' => null,
        'ends_till' => null,
    ]);

    $cartRuleCoupon = CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $couponCode = fake()->numerify('bagisto-########'),
        'type' => 0,
        'is_primary' => 1,
    ]);

    foreach ([
        'sales.taxes.categories.product' => $taxCategory->id,
        'sales.taxes.calculation.based_on' => 'shipping_address',
        'sales.taxes.calculation.product_prices' => 'excluding_tax',
        'sales.taxes.calculation.apply_tax_on' => 'after_discount',
    ] as $code => $value) {
        CoreConfig::updateOrCreate(['code' => $code], ['value' => $value]);
    }

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
    ]);

    CartItem::factory()->create([
        'product_id' => $product->id,
        'sku' => $product->sku,
        'type' => $product->type,
        'name' => $product->name,
        'cart_id' => $cart->id,
    ]);

    CustomerAddress::factory()->create([
        'email' => $customer->email,
        'country' => $countryCode,
        'cart_id' => $cart->id,
        'address_type' => 'cart_billing',
    ]);

    CustomerAddress::factory()->create([
        'email' => $customer->email,
        'country' => $countryCode,
        'cart_id' => $cart->id,
        'address_type' => 'cart_shipping',
    ]);

    // Act.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.coupon.apply'), [
        'code' => $couponCode,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.coupon.success-apply'))
        ->assertJsonPath('data.id', $cart->id);

    $subTotal = $response->json('data.sub_total');

    $discountAmount = $response->json('data.discount_amount');

    // Assert: the coupon discount was applied.
    expect($discountAmount)->toBeGreaterThan(0);

    // Assert: tax is calculated on the net amount after the discount is removed.
    $this->assertPrice(round(($subTotal - $discountAmount) * $taxRate->tax_rate / 100, 2), $response->json('data.tax_total'));

    // Assert: this is strictly less than tax calculated on the full (pre-discount) price.
    expect((float) $response->json('data.tax_total'))->toBeLessThan(round($subTotal * $taxRate->tax_rate / 100, 2));

    // Assert: grand total reconciles as sub total + tax - discount.
    $this->assertPrice(round($subTotal + $response->json('data.tax_total') - $discountAmount, 2), $response->json('data.grand_total'));
});

it('should calculate tax on the original price when apply tax on is set to before discount', function () {
    // Arrange.
    $taxCategory = TaxCategory::factory()->create();

    $taxRate = TaxRate::factory()->create([
        'zip_code' => '',
        'state' => '',
        'country' => $countryCode = 'IN',
        'tax_rate' => 10,
    ]);

    TaxMap::factory()->create([
        'tax_category_id' => $taxCategory->id,
        'tax_rate_id' => $taxRate->id,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            4 => 'tax_category_id',
            5 => 'new',
            6 => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'tax_category_id' => [
                'integer_value' => $taxCategory->id,
            ],
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => 1000,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_percent',
        'discount_amount' => 10,
        'usage_per_customer' => 0,
        'uses_per_coupon' => 0,
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 0,
        'use_auto_generation' => 0,
        'times_used' => 0,
        'coupon_type' => 1,
        'end_other_rules' => 0,
        'uses_attribute_conditions' => 0,
        'discount_step' => 0,
        'free_shipping' => 0,
        'sort_order' => 0,
        'conditions' => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from' => null,
        'ends_till' => null,
    ]);

    $cartRuleCoupon = CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $couponCode = fake()->numerify('bagisto-########'),
        'type' => 0,
        'is_primary' => 1,
    ]);

    foreach ([
        'sales.taxes.categories.product' => $taxCategory->id,
        'sales.taxes.calculation.based_on' => 'shipping_address',
        'sales.taxes.calculation.product_prices' => 'excluding_tax',
        'sales.taxes.calculation.apply_tax_on' => 'before_discount',
    ] as $code => $value) {
        CoreConfig::updateOrCreate(['code' => $code], ['value' => $value]);
    }

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
    ]);

    CartItem::factory()->create([
        'product_id' => $product->id,
        'sku' => $product->sku,
        'type' => $product->type,
        'name' => $product->name,
        'cart_id' => $cart->id,
    ]);

    CustomerAddress::factory()->create([
        'email' => $customer->email,
        'country' => $countryCode,
        'cart_id' => $cart->id,
        'address_type' => 'cart_billing',
    ]);

    CustomerAddress::factory()->create([
        'email' => $customer->email,
        'country' => $countryCode,
        'cart_id' => $cart->id,
        'address_type' => 'cart_shipping',
    ]);

    // Act.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.coupon.apply'), [
        'code' => $couponCode,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.coupon.success-apply'))
        ->assertJsonPath('data.id', $cart->id);

    $subTotal = $response->json('data.sub_total');

    $discountAmount = $response->json('data.discount_amount');

    // Assert: the coupon discount was applied.
    expect($discountAmount)->toBeGreaterThan(0);

    // Assert: tax is calculated on the full (pre-discount) price - the default behaviour.
    $this->assertPrice(round($subTotal * $taxRate->tax_rate / 100, 2), $response->json('data.tax_total'));
});

it('should not change the inclusive-tax grand total when apply tax on is set to after discount', function () {
    // Arrange.
    $taxCategory = TaxCategory::factory()->create();

    $taxRate = TaxRate::factory()->create([
        'zip_code' => '',
        'state' => '',
        'country' => $countryCode = 'IN',
        'tax_rate' => 10,
    ]);

    TaxMap::factory()->create([
        'tax_category_id' => $taxCategory->id,
        'tax_rate_id' => $taxRate->id,
    ]);

    // Inclusive price: 1100 gross = 1000 net + 100 tax at 10%.
    $product = (new ProductFaker([
        'attributes' => [
            4 => 'tax_category_id',
            5 => 'new',
            6 => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'tax_category_id' => [
                'integer_value' => $taxCategory->id,
            ],
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => 1100,
            ],
        ],
    ]))->getSimpleProductFactory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_percent',
        'discount_amount' => 10,
        'usage_per_customer' => 0,
        'uses_per_coupon' => 0,
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 0,
        'use_auto_generation' => 0,
        'times_used' => 0,
        'coupon_type' => 1,
        'end_other_rules' => 0,
        'uses_attribute_conditions' => 0,
        'discount_step' => 0,
        'free_shipping' => 0,
        'sort_order' => 0,
        'conditions' => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from' => null,
        'ends_till' => null,
    ]);

    $cartRuleCoupon = CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $couponCode = fake()->numerify('bagisto-########'),
        'type' => 0,
        'is_primary' => 1,
    ]);

    foreach ([
        'sales.taxes.categories.product' => $taxCategory->id,
        'sales.taxes.calculation.based_on' => 'shipping_address',
        'sales.taxes.calculation.product_prices' => 'including_tax',
        'sales.taxes.calculation.apply_tax_on' => 'after_discount',
    ] as $code => $value) {
        CoreConfig::updateOrCreate(['code' => $code], ['value' => $value]);
    }

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
    ]);

    CartItem::factory()->create([
        'product_id' => $product->id,
        'sku' => $product->sku,
        'type' => $product->type,
        'name' => $product->name,
        'cart_id' => $cart->id,
    ]);

    CustomerAddress::factory()->create([
        'email' => $customer->email,
        'country' => $countryCode,
        'cart_id' => $cart->id,
        'address_type' => 'cart_billing',
    ]);

    CustomerAddress::factory()->create([
        'email' => $customer->email,
        'country' => $countryCode,
        'cart_id' => $cart->id,
        'address_type' => 'cart_shipping',
    ]);

    // Act.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.coupon.apply'), [
        'code' => $couponCode,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.coupon.success-apply'))
        ->assertJsonPath('data.id', $cart->id);

    $subTotalInclTax = $response->json('data.sub_total_incl_tax');

    $discountAmount = $response->json('data.discount_amount');

    // Assert: the coupon discount was applied.
    expect($discountAmount)->toBeGreaterThan(0);

    // Assert: in inclusive mode the customer always pays the discounted GROSS price,
    // regardless of the apply-tax-on setting (no double discounting of the tax portion).
    $this->assertPrice(round($subTotalInclTax - $discountAmount, 2), $response->json('data.grand_total'));

    // Assert: tax is still the full amount extracted from the gross price (the setting
    // only affects exclusive pricing, so inclusive tax is unchanged).
    $this->assertPrice(round($subTotalInclTax * $taxRate->tax_rate / (100 + $taxRate->tax_rate), 2), $response->json('data.tax_total'));
});
