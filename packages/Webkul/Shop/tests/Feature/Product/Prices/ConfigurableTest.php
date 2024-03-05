<?php

use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\CatalogRule\Models\CatalogRuleProductPrice;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\ProductCustomerGroupPrice;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

it('should fails the validation error when the certain inputs not provided when add a configurable product to the cart with a cart rule of the no coupon type for all customer group type', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 2000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(1, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 0,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a configurable product to the cart with a cart rule of the no coupon type for all customer group type', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 2000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(1, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 0,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertEquals(round($childProduct->price - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($childProduct->price, 2), round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($cartRule->discount_amount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when add a configurable product to the cart with a cart rule of the no coupon type for guest customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 2000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(1, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 0,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a configurable product to the cart with a cart rule of the no coupon type for guest customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 2000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(1, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 0,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertEquals(round($childProduct->price - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($childProduct->price, 2), round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($cartRule->discount_amount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should fails the validation error when certain inputs not provided when add a configurable product to the cart with a cart rule of the no coupon type for general customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 2000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create();

    CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([2]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(1, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 0,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a configurable product to the cart with a cart rule of the no coupon type for general customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 2000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([2]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(1, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 0,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertEquals(round($childProduct->price - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($childProduct->price, 2), round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($cartRule->discount_amount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when add a configurable product to the cart with a cart rule of the no coupon type for wholesaler customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 2000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([3]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(1, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 0,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a configurable product to the cart with a cart rule of the no coupon type for wholesaler customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 2000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([3]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(1, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 0,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertEquals(round($childProduct->price - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($childProduct->price, 2), round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($cartRule->discount_amount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when add a configurable product to the cart with a cart rule of the specific coupon type for all customer grouped types', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(20, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 1,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product->id, [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]);

    $cartTemp = new \stdClass();

    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Act and Assert
    postJson(route('shop.api.checkout.cart.coupon.apply'))
        ->assertJsonValidationErrorFor('code')
        ->assertUnprocessable();
});

it('should add a configurable product to the cart with a cart rule of the specific coupon type for all customer grouped types', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(20, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 1,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product->id, [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]);

    $cartTemp = new \stdClass();

    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.coupon.apply'), [
        'code' => $couponCode,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.coupon.success-apply'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertEquals(round($childProduct->price - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($childProduct->price, 2), round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($cartRule->discount_amount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should fails the validation error when certain inputs not provided when add a configurable product to the cart with a cart rule of the specific coupon type for guest customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(20, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 1,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product->id, [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]);

    $cartTemp = new \stdClass();

    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Act and Assert
    postJson(route('shop.api.checkout.cart.coupon.apply'))
        ->assertJsonValidationErrorFor('code')
        ->assertUnprocessable();
});

it('should add a configurable product to the cart with a cart rule of the specific coupon type for guest customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(20, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 1,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product->id, [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]);

    $cartTemp = new \stdClass();

    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.coupon.apply'), [
        'code' => $couponCode,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.coupon.success-apply'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertEquals(round($childProduct->price - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals($childProduct->price, round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($cartRule->discount_amount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when  add a configurable product to the cart with a cart rule of the specific coupon type for general customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([2]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(20, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 1,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product->id, [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]);

    $cartTemp = new \stdClass();

    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.coupon.apply'))
        ->assertJsonValidationErrorFor('code')
        ->assertUnprocessable();
});

it('should add a configurable product to the cart with a cart rule of the specific coupon type for general customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([2]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(20, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 1,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product->id, [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]);

    $cartTemp = new \stdClass();

    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.coupon.apply'), [
        'code' => $couponCode,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.coupon.success-apply'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertEquals(round($childProduct->price - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($childProduct->price, 2), round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($cartRule->discount_amount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain input not provided when add a configurable product to the cart with a cart rule of the specific coupon type for wholesaler customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([3]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(20, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 1,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product->id, [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]);

    $cartTemp = new \stdClass();

    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.coupon.apply'))
        ->assertJsonValidationErrorFor('code')
        ->assertUnprocessable();
});

it('should add a configurable product to the cart with a cart rule of the specific coupon type for wholesaler customer', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([3]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => rand(20, 50),
        'usage_per_customer'        => rand(1, 50),
        'uses_per_coupon'           => rand(1, 50),
        'condition_type'            => 2,
        'status'                    => 1,
        'discount_quantity'         => 1,
        'apply_to_shipping'         => 1,
        'use_auto_generation'       => 0,
        'times_used'                => 0,
        'coupon_type'               => 1,
        'end_other_rules'           => 0,
        'uses_attribute_conditions' => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product->id, [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]);

    $cartTemp = new \stdClass();

    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.coupon.apply'), [
        'code' => $couponCode,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.coupon.success-apply'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertEquals(round($childProduct->price - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($childProduct->price, 2), round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($cartRule->discount_amount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should check tax is applying for the configurable product into the cart for configurable product', function () {
    // Arrange
    $taxCategory = TaxCategory::factory()->create();

    $taxRate = TaxRate::factory()->create([
        'zip_code' => '',
        'country'  => $countryCode = 'IN',
    ]);

    TaxMap::factory()->create([
        'tax_category_id' => $taxCategory->id,
        'tax_rate_id'     => $taxRate->id,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            4  => 'tax_category_id',
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
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
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create();

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product->id, [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]);

    $cartTemp = new \stdClass();
    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    CustomerAddress::factory()->create([
        'email'        => $customer->email,
        'country'      => $countryCode,
        'cart_id'      => $cart->id,
        'address_type' => 'cart_billing',
    ]);

    CustomerAddress::factory()->create([
        'email'        => $customer->email,
        'country'      => $countryCode,
        'cart_id'      => $cart->id,
        'address_type' => 'cart_shipping',
    ]);

    cart()->collectTotals();

    $cart->refresh();

    getJson(route('shop.checkout.onepage.summary'))
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.tax_total', $cart->tax_total)
        ->assertJsonPath('data.base_tax_total', $cart->base_tax_total)
        ->assertJsonPath('data.grand_total', $cart->grand_total);
});

it('should check customer group price for guest customer with fixed price type for configurable product', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(5, 10),
        'value_type'        => 'fixed',
        'value'             => rand(20, 50),
        'product_id'        => $childProduct->id,
        'customer_group_id' => 1,
    ]);

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => $productCustomerPrice->qty,
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $productCustomerPrice->qty)
        ->assertJsonPath('data.items_count', 1);

    $this->assertEquals(round($productCustomerPrice->value * $productCustomerPrice->qty, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($productCustomerPrice->value * $productCustomerPrice->qty, 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain input not provided when check customer group price for general customer with fixed price type for configurable product', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create();

    $childProduct = $product->variants()->first();

    $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(5, 10),
        'value_type'        => 'fixed',
        'value'             => rand(20, 50),
        'product_id'        => $childProduct->id,
        'customer_group_id' => 2,
    ]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => $productCustomerPrice->qty,
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check customer group price for general customer with fixed price type for configurable product', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create();

    $childProduct = $product->variants()->first();

    $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(5, 10),
        'value_type'        => 'fixed',
        'value'             => rand(20, 50),
        'product_id'        => $childProduct->id,
        'customer_group_id' => 2,
    ]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => $productCustomerPrice->qty,
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $productCustomerPrice->qty)
        ->assertJsonPath('data.items_count', 1);

    $this->assertEquals(round($productCustomerPrice->value * $productCustomerPrice->qty, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($productCustomerPrice->value * $productCustomerPrice->qty, 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for wholesaler customer with fixed price type for configurable product', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $childProduct = $product->variants()->first();

    $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(5, 10),
        'value_type'        => 'fixed',
        'value'             => rand(20, 50),
        'product_id'        => $childProduct->id,
        'customer_group_id' => 3,
    ]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => $productCustomerPrice->qty,
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check customer group price for wholesaler customer with fixed price type for configurable product', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $childProduct = $product->variants()->first();

    $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(5, 10),
        'value_type'        => 'fixed',
        'value'             => rand(20, 50),
        'product_id'        => $childProduct->id,
        'customer_group_id' => 3,
    ]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => $productCustomerPrice->qty,
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $productCustomerPrice->qty)
        ->assertJsonPath('data.items_count', 1);

    $this->assertEquals(round($productCustomerPrice->value * $productCustomerPrice->qty, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($productCustomerPrice->value * $productCustomerPrice->qty, 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for guest customer with discount price type for configurable product', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(5, 10),
        'value_type'        => 'discount',
        'value'             => rand(20, 50),
        'product_id'        => $childProduct->id,
        'customer_group_id' => 1,
    ]);

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => $productCustomerPrice->qty,
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check customer group price for guest customer with discount price type for configurable product', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(5, 10),
        'value_type'        => 'discount',
        'value'             => rand(20, 50),
        'product_id'        => $childProduct->id,
        'customer_group_id' => 1,
    ]);

    $grandTotal = ($childProduct->price - ($childProduct->price * $productCustomerPrice->value / 100)) * $productCustomerPrice->qty;

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => $productCustomerPrice->qty,
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $productCustomerPrice->qty)
        ->assertJsonPath('data.items_count', 1);

    $this->assertEquals(round($grandTotal, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($grandTotal, 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for general customer with discount price type for configurable product', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create();

    $childProduct = $product->variants()->first();

    $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(5, 10),
        'value_type'        => 'discount',
        'value'             => rand(20, 50),
        'product_id'        => $childProduct->id,
        'customer_group_id' => 2,
    ]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => $productCustomerPrice->qty,
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check customer group price for general customer with discount price type for configurable product', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create();

    $childProduct = $product->variants()->first();

    $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(5, 10),
        'value_type'        => 'discount',
        'value'             => rand(20, 50),
        'product_id'        => $childProduct->id,
        'customer_group_id' => 2,
    ]);

    $grandTotal = (($childProduct->price - ($childProduct->price * ($productCustomerPrice->value / 100))) * $productCustomerPrice->qty);

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => $productCustomerPrice->qty,
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $productCustomerPrice->qty)
        ->assertJsonPath('data.items_count', 1);

    $this->assertEquals(round(floor($grandTotal), 2), round(floor($response['data']['grand_total']), 2), '', 0.00000001);

    $this->assertEquals(round(floor($grandTotal), 2), round(floor($response['data']['sub_total']), 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for wholesaler customer with discount price type for configurable product', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $childProduct = $product->variants()->first();

    $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(5, 10),
        'value_type'        => 'discount',
        'value'             => rand(20, 50),
        'product_id'        => $childProduct->id,
        'customer_group_id' => 3,
    ]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => $productCustomerPrice->qty,
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check customer group price for wholesaler customer with discount price type for configurable product', function () {
    // Arrange
    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $childProduct = $product->variants()->first();

    $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(5, 10),
        'value_type'        => 'discount',
        'value'             => rand(20, 50),
        'product_id'        => $childProduct->id,
        'customer_group_id' => 3,
    ]);

    $grandTotal = (($childProduct->price - ($childProduct->price * ($productCustomerPrice->value / 100))) * $productCustomerPrice->qty);

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => $productCustomerPrice->qty,
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $productCustomerPrice->qty)
        ->assertJsonPath('data.items_count', 1);

    $this->assertEquals(round($grandTotal, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($grandTotal, 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for percentage price for configurable product for guest customer into cart', function () {
    // Arrange
    CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([1]);
    })->create([
        'status'     => 1,
        'sort_order' => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for percentage price for configurable product for guest customer into cart', function () {
    // Arrange
    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([1]);
    })->create([
        'status'     => 1,
        'sort_order' => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    $grandTotal = $childProduct->price - ($childProduct->price * ($catalogRule->discount_amount / 100));

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round($grandTotal, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
    $this->assertEquals(round($grandTotal, 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for percentage price for configurable product for general customer into cart', function () {
    // Arrange
    $customer = Customer::factory()->create();

    CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([2]);
    })->create([
        'status'     => 1,
        'sort_order' => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for percentage price for configurable product for general customer into cart', function () {
    // Arrange
    $customer = Customer::factory()->create();

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([2]);
    })->create([
        'status'     => 1,
        'sort_order' => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    $grandTotal = $childProduct->price - ($childProduct->price * ($catalogRule->discount_amount / 100));

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round($grandTotal, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
    $this->assertEquals(round($grandTotal, 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for percentage price for configurable product for wholesaler customer into cart', function () {
    // Arrange
    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([3]);
    })->create([
        'status'     => 1,
        'sort_order' => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for percentage price for configurable product for wholesaler customer into cart', function () {
    // Arrange
    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([3]);
    })->create([
        'status'     => 1,
        'sort_order' => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    $grandTotal = $product->price - ($childProduct->price * ($catalogRule->discount_amount / 100));

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round($grandTotal, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
    $this->assertEquals(round($grandTotal, 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided check discount price if catalog rule applied for fixed price for configurable product for guest customer into cart', function () {
    // Arrange
    CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([1]);
    })->create([
        'status'      => 1,
        'sort_order'  => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for fixed price for configurable product for guest customer into cart', function () {
    // Arrange
    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([1]);
    })->create([
        'status'      => 1,
        'sort_order'  => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round($childProduct->price - $catalogRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
    $this->assertEquals(round($childProduct->price - $catalogRule->discount_amount, 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for fixed price for configurable product for general customer into cart', function () {
    // Arrange
    $customer = Customer::factory()->create();

    CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([2]);
    })->create([
        'status'      => 1,
        'sort_order'  => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for fixed price for configurable product for general customer into cart', function () {
    // Arrange
    $customer = Customer::factory()->create();

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([2]);
    })->create([
        'status'      => 1,
        'sort_order'  => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round($childProduct->price - $catalogRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
    $this->assertEquals(round($childProduct->price - $catalogRule->discount_amount, 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for fixed price for configurable product for wholesaler customer into cart', function () {
    // Arrange
    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([3]);
    })->create([
        'status'      => 1,
        'sort_order'  => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for fixed price for configurable product for wholesaler customer into cart', function () {
    // Arrange
    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([3]);
    })->create([
        'status'      => 1,
        'sort_order'  => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'product_id'                   => $product->id,
        'is_buy_now'                   => '0',
        'rating'                       => '0',
        'quantity'                     => '1',
        'super_attribute'              => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round($childProduct->price - $catalogRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
    $this->assertEquals(round($childProduct->price - $catalogRule->discount_amount, 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should check discount price if catalog rule applied for fixed price for configurable product for guest customer', function () {
    // Arrange
    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([1]);
    })->create([
        'status'      => 1,
        'sort_order'  => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->assertModelWise([
        CatalogRuleProductPrice::class => [
            [
                'price'             => $childProduct->price - $catalogRule->discount_amount,
                'customer_group_id' => 1,
                'catalog_rule_id'   => $catalogRule->id,
                'product_id'        => $childProduct->id,
            ],
        ],
    ]);
});

it('should check discount price if catalog rule applied for fixed price for configurable product for general customer', function () {
    // Arrange
    $customer = Customer::factory()->create();

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([2]);
    })->create([
        'status'      => 1,
        'sort_order'  => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->loginAsCustomer($customer);

    $this->assertModelWise([
        CatalogRuleProductPrice::class => [
            [
                'price'             => $childProduct->price - $catalogRule->discount_amount,
                'customer_group_id' => 2,
                'catalog_rule_id'   => $catalogRule->id,
                'product_id'        => $childProduct->id,
            ],
        ],
    ]);
});

it('should check discount price if catalog rule applied for fixed price for configurable product for wholesaler customer', function () {
    // Arrange
    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([3]);
    })->create([
        'status'      => 1,
        'sort_order'  => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->loginAsCustomer($customer);

    $this->assertModelWise([
        CatalogRuleProductPrice::class => [
            [
                'price'             => $childProduct->price - $catalogRule->discount_amount,
                'customer_group_id' => 3,
                'catalog_rule_id'   => $catalogRule->id,
                'product_id'        => $childProduct->id,
            ],
        ],
    ]);
});

it('should check discount price if catalog rule applied for percentage price for configurable product for guest customer', function () {
    // Arrange
    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([1]);
    })->create([
        'status'      => 1,
        'sort_order'  => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
            26 => 'guest_checkout',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->assertModelWise([
        CatalogRuleProductPrice::class => [
            [
                'price'             => $childProduct->price - ($childProduct->price * ($catalogRule->discount_amount / 100)),
                'customer_group_id' => 1,
                'catalog_rule_id'   => $catalogRule->id,
                'product_id'        => $childProduct->id,
            ],
        ],
    ]);
});

it('should check discount price if catalog rule applied for percentage price for configurable product for general customer', function () {
    // Arrange
    $customer = Customer::factory()->create();

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([2]);
    })->create([
        'status'      => 1,
        'sort_order'  => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->loginAsCustomer($customer);

    $this->assertModelWise([
        CatalogRuleProductPrice::class => [
            [
                'price'             => $childProduct->price - ($childProduct->price * ($catalogRule->discount_amount / 100)),
                'customer_group_id' => 2,
                'catalog_rule_id'   => $catalogRule->id,
                'product_id'        => $childProduct->id,
            ],
        ],
    ]);
});

it('should check discount price if catalog rule applied for percentage price for configurable product for wholesaler customer', function () {
    // Arrange
    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([3]);
    })->create([
        'status'      => 1,
        'sort_order'  => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5  => 'new',
            6  => 'featured',
            11 => 'price',
        ],
        'attribute_value' => [
            'new' => [
                'boolean_value' => true,
            ],
            'featured' => [
                'boolean_value' => true,
            ],
            'price' => [
                'float_value' => rand(1000, 5000),
            ],
        ],
    ]))->getConfigurableProductFactory()->create();

    $childProduct = $product->variants()->first();

    // Act and Assert
    $this->loginAsCustomer($customer);

    $this->assertModelWise([
        CatalogRuleProductPrice::class => [
            [
                'price'             => $childProduct->price - ($childProduct->price * ($catalogRule->discount_amount / 100)),
                'customer_group_id' => 3,
                'catalog_rule_id'   => $catalogRule->id,
                'product_id'        => $childProduct->id,
            ],
        ],
    ]);
});
