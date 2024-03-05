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

it('should fails the validation error when the certain inputs not provided when add a bundle product to the cart with a cart rule of the no coupon type for all customer group type', function () {
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
    ]))->getBundleProductFactory()->create();

    CartRule::factory()->afterCreating(function (CartRule $cartRule) {
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
        'coupon_type'               => 0,
        'end_other_rules'           => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "50000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
        'uses_attribute_conditions' => 0,
    ]);

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a bundle product to the cart with a cart rule of the no coupon type for all customer group type', function () {
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
    ]))->getBundleProductFactory()->create();

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
        'coupon_type'               => 0,
        'end_other_rules'           => 0,
        'discount_step'             => 0,
        'free_shipping'             => 0,
        'sort_order'                => 0,
        'conditions'                => json_decode('[{"value": "50000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from'               => null,
        'ends_till'                 => null,
        'uses_attribute_conditions' => 0,
    ]);

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals($cartRule->discount_amount, $response['data']['discount_amount']);

    $this->assertEquals(round($grandTotal - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($grandTotal), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when add a bundle product to the cart with a cart rule of the no coupon type for guest customer', function () {
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
    ]))->getBundleProductFactory()->create();

    CartRule::factory()->afterCreating(function (CartRule $cartRule) {
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

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a bundle product to the cart with a cart rule of the no coupon type for guest customer', function () {
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
    ]))->getBundleProductFactory()->create();

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

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'));

    $this->assertEquals($cartRule->discount_amount, $response['data']['discount_amount']);

    $this->assertEquals(round($grandTotal - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($grandTotal), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when add a bundle product to the cart with a cart rule of the no coupon type for general customer', function () {
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
    ]))->getBundleProductFactory()->create();

    CartRule::factory()->afterCreating(function (CartRule $cartRule) {
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

    $customer = Customer::factory()->create(['customer_group_id' => 2]);

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a bundle product to the cart with a cart rule of the no coupon type for general customer', function () {
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
    ]))->getBundleProductFactory()->create();

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

    $customer = Customer::factory()->create(['customer_group_id' => 2]);

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'));

    $this->assertEquals($cartRule->discount_amount, $response['data']['discount_amount']);

    $this->assertEquals(round($grandTotal - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($grandTotal), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when add a bundle product to the cart with a cart rule of the no coupon type for wholesaler customer', function () {
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
    ]))->getBundleProductFactory()->create();

    CartRule::factory()->afterCreating(function (CartRule $cartRule) {
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

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should add a bundle product to the cart with a cart rule of the no coupon type for wholesaler customer', function () {
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
    ]))->getBundleProductFactory()->create();

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

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'));

    $this->assertEquals($cartRule->discount_amount, $response['data']['discount_amount']);

    $this->assertEquals(round($grandTotal - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($grandTotal), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when add a bundle product to the cart with a cart rule of the specific coupon type for all customer grouped types', function () {
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
    ]))->getBundleProductFactory()->create();

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

    $bundleOptions = [];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    $cart = cart()->addProduct($product->id, [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]);

    $cartTemp = new \stdClass();

    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Act and Assert
    postJson(route('shop.api.checkout.cart.coupon.apply'))
        ->assertJsonValidationErrorFor('code')
        ->assertUnprocessable();
});

it('should add a bundle product to the cart with a cart rule of the specific coupon type for all customer grouped types', function () {
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
    ]))->getBundleProductFactory()->create();

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

    $bundleOptions = [];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    $cart = cart()->addProduct($product->id, [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_options'    => $bundleOptions['bundle_options'],
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
        ->assertJsonPath('data.id', $cart->id);

    $this->assertEquals(round($grandTotal, 2), round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($grandTotal - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($cartRule->discount_amount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should fails the validation error when certain inputs not provided when add a bundle product to the cart with a cart rule of the specific coupon type for guest customer', function () {
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
    ]))->getBundleProductFactory()->create();

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

    $bundleOptions = [];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    $cart = cart()->addProduct($product->id, [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]);

    $cartTemp = new \stdClass();

    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Act and Assert
    postJson(route('shop.api.checkout.cart.coupon.apply'))
        ->assertJsonValidationErrorFor('code')
        ->assertUnprocessable();
});

it('should add a bundle product to the cart with a cart rule of the specific coupon type for guest customer', function () {
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
    ]))->getBundleProductFactory()->create();

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

    $bundleOptions = [];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    $cart = cart()->addProduct($product->id, [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_options'    => $bundleOptions['bundle_options'],
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
        ->assertJsonPath('data.id', $cart->id);

    $this->assertEquals(round($grandTotal, 2), round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($grandTotal - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($cartRule->discount_amount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when add a bundle product to the cart with a cart rule of the specific coupon type for general customer', function () {
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
    ]))->getBundleProductFactory()->create();

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

    $bundleOptions = [];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    $cart = cart()->addProduct($product->id, [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]);

    $cartTemp = new \stdClass();

    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Act and Assert
    $this->loginAsCustomer();

    postJson(route('shop.api.checkout.cart.coupon.apply'))
        ->assertJsonValidationErrorFor('code')
        ->assertUnprocessable();
});

it('should add a bundle product to the cart with a cart rule of the specific coupon type for general customer', function () {
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
    ]))->getBundleProductFactory()->create();

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

    $bundleOptions = [];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    $cart = cart()->addProduct($product->id, [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]);

    $cartTemp = new \stdClass();

    $cartTemp->id = $cart->id;

    session()->put('cart', $cartTemp);

    // Act and Assert
    $this->loginAsCustomer();

    $response = postJson(route('shop.api.checkout.cart.coupon.apply'), [
        'code' => $couponCode,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.coupon.success-apply'))
        ->assertJsonPath('data.id', $cart->id);

    $this->assertEquals(round($grandTotal, 2), round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($grandTotal - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($cartRule->discount_amount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when add a bundle product to the cart with a cart rule of the specific coupon type for wholesaler customer', function () {
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
    ]))->getBundleProductFactory()->create();

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

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $bundleOptions = [];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    $cart = cart()->addProduct($product->id, [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_options'    => $bundleOptions['bundle_options'],
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

it('should add a bundle product to the cart with a cart rule of the specific coupon type for wholesaler customer', function () {
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
    ]))->getBundleProductFactory()->create();

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

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $bundleOptions = [];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    $cart = cart()->addProduct($product->id, [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_options'    => $bundleOptions['bundle_options'],
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
        ->assertJsonPath('data.id', $cart->id);

    $this->assertEquals(round($grandTotal, 2), round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($grandTotal - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);

    $this->assertEquals(round($cartRule->discount_amount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should check tax is applying for the bundle product into the cart for bundle product', function () {
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
    ]))->getBundleProductFactory()->create();

    $customer = Customer::factory()->create();

    $bundleOptions = [];

    $grandTotal = 0;

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $grandTotal += $bundleOption->product->price;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    $cart = cart()->addProduct($product->id, [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_options'    => $bundleOptions['bundle_options'],
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

    // Act and Assert
    getJson(route('shop.checkout.onepage.summary'))
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.tax_total', $cart->tax_total)
        ->assertJsonPath('data.base_tax_total', $cart->base_tax_total)
        ->assertJsonPath('data.grand_total', $cart->grand_total);
});

it('should fails the validation error when the certain inputs not provided check customer group price for guest customer with fixed price type for bundle product', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],

        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    foreach ($product->bundle_options()->with('bundle_option_products')->get() as $option) {
        $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => $quantity = 2,
            'value_type'        => 'fixed',
            'value'             => rand(20, 50),
            'product_id'        => $option->bundle_option_products[0]->product_id,
            'customer_group_id' => 1,
        ]);

        $bundleOptions['bundle_options'][$option->id] = [$option->id];

        $bundleOptions['bundle_option_quantities'][$option->id] = $quantity;

        $grandTotal += $customerGroupPrice->value;
    }

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check customer group price for guest customer with fixed price type for bundle product', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],

        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    foreach ($product->bundle_options()->with('bundle_option_products')->get() as $option) {
        $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => $quantity = 2,
            'value_type'        => 'fixed',
            'value'             => rand(20, 50),
            'product_id'        => $option->bundle_option_products[0]->product_id,
            'customer_group_id' => 1,
        ]);

        $bundleOptions['bundle_options'][$option->id] = [$option->id];

        $bundleOptions['bundle_option_quantities'][$option->id] = $quantity;

        $grandTotal += $customerGroupPrice->value;
    }

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round($grandTotal * $quantity, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for general customer with fixed price type for bundle product', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    foreach ($product->bundle_options()->with('bundle_option_products')->get() as $option) {
        $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => $quantity = 10,
            'value_type'        => 'fixed',
            'value'             => rand(20, 50),
            'product_id'        => $option->bundle_option_products[0]->product_id,
            'customer_group_id' => 2,
        ]);

        $bundleOptions['bundle_options'][$option->id] = [$option->id];

        $bundleOptions['bundle_option_quantities'][$option->id] = $quantity;

        $grandTotal += $customerGroupPrice->value;
    }

    $customer = Customer::factory()->create();

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check customer group price for general customer with fixed price type for bundle product', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    foreach ($product->bundle_options()->with('bundle_option_products')->get() as $option) {
        $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => $quantity = 10,
            'value_type'        => 'fixed',
            'value'             => rand(20, 50),
            'product_id'        => $option->bundle_option_products[0]->product_id,
            'customer_group_id' => 2,
        ]);

        $bundleOptions['bundle_options'][$option->id] = [$option->id];

        $bundleOptions['bundle_option_quantities'][$option->id] = $quantity;

        $grandTotal += $customerGroupPrice->value;
    }

    $customer = Customer::factory()->create();

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round($grandTotal * $quantity, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for wholesaler customer with fixed price type for bundle product', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],

        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    foreach ($product->bundle_options()->with('bundle_option_products')->get() as $option) {
        $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => $quantity = 50,
            'value_type'        => 'fixed',
            'value'             => rand(20, 50),
            'product_id'        => $option->bundle_option_products[0]->product_id,
            'customer_group_id' => 3,
        ]);

        $bundleOptions['bundle_options'][$option->id] = [$option->id];

        $bundleOptions['bundle_option_quantities'][$option->id] = $quantity;

        $grandTotal += $customerGroupPrice->value;
    }

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check customer group price for wholesaler customer with fixed price type for bundle product', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],

        'bundle_options'           => [],
    ];

    $grandTotal = 0;

    foreach ($product->bundle_options()->with('bundle_option_products')->get() as $option) {
        $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => $quantity = 50,
            'value_type'        => 'fixed',
            'value'             => rand(20, 50),
            'product_id'        => $option->bundle_option_products[0]->product_id,
            'customer_group_id' => 3,
        ]);

        $bundleOptions['bundle_options'][$option->id] = [$option->id];

        $bundleOptions['bundle_option_quantities'][$option->id] = $quantity;

        $grandTotal += $customerGroupPrice->value;
    }

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round($grandTotal * $quantity, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for guest customer with discount price type for bundle product', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'grand_total'              => [],
    ];

    foreach ($product->bundle_options()->with('bundle_option_products.product')->get() as $option) {
        $bundleProduct = $option->bundle_option_products[0]->product;

        $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => $quantity = 2,
            'value_type'        => 'discount',
            'value'             => rand(20, 50),
            'product_id'        => $bundleProduct->id,
            'customer_group_id' => 1,
        ]);

        $bundleOptions['bundle_options'][$option->id] = [$option->id];

        $bundleOptions['bundle_option_quantities'][$option->id] = $quantity;

        $bundleOptions['grand_total'][] = ($bundleProduct->price - ($bundleProduct->price * $customerGroupPrice->value / 100)) * $customerGroupPrice->qty;
    }

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check customer group price for guest customer with discount price type for bundle product', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'grand_total'              => [],
    ];

    foreach ($product->bundle_options()->with('bundle_option_products.product')->get() as $option) {
        $bundleProduct = $option->bundle_option_products[0]->product;

        $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => $quantity = 2,
            'value_type'        => 'discount',
            'value'             => rand(20, 50),
            'product_id'        => $bundleProduct->id,
            'customer_group_id' => 1,
        ]);

        $bundleOptions['bundle_options'][$option->id] = [$option->id];

        $bundleOptions['bundle_option_quantities'][$option->id] = $quantity;

        $bundleOptions['grand_total'][] = ($bundleProduct->price - ($bundleProduct->price * $customerGroupPrice->value / 100)) * $customerGroupPrice->qty;
    }

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round(array_sum($bundleOptions['grand_total']), 2), round($response['data']['grand_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for general customer with discount price type for bundle product', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'grand_total'              => [],
    ];

    $customer = Customer::factory()->create();

    foreach ($product->bundle_options()->with('bundle_option_products.product')->get() as $option) {
        $bundleProduct = $option->bundle_option_products[0]->product;

        $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => $quantity = 2,
            'value_type'        => 'discount',
            'value'             => rand(20, 50),
            'product_id'        => $bundleProduct->id,
            'customer_group_id' => 2,
        ]);

        $bundleOptions['bundle_options'][$option->id] = [$option->id];

        $bundleOptions['bundle_option_quantities'][$option->id] = $quantity;

        $bundleOptions['grand_total'][] = ($bundleProduct->price - ($bundleProduct->price * $customerGroupPrice->value / 100)) * $customerGroupPrice->qty;
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check customer group price for general customer with discount price type for bundle product', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'grand_total'              => [],
    ];

    $customer = Customer::factory()->create();

    foreach ($product->bundle_options()->with('bundle_option_products.product')->get() as $option) {
        $bundleProduct = $option->bundle_option_products[0]->product;

        $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => $quantity = 2,
            'value_type'        => 'discount',
            'value'             => rand(20, 50),
            'product_id'        => $bundleProduct->id,
            'customer_group_id' => 2,
        ]);

        $bundleOptions['bundle_options'][$option->id] = [$option->id];

        $bundleOptions['bundle_option_quantities'][$option->id] = $quantity;

        $bundleOptions['grand_total'][] = ($bundleProduct->price - ($bundleProduct->price * $customerGroupPrice->value / 100)) * $customerGroupPrice->qty;
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round(array_sum($bundleOptions['grand_total']), 2), round($response['data']['grand_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for wholesaler customer with discount price type for bundle product', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'grand_total'              => [],
    ];

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    foreach ($product->bundle_options()->with('bundle_option_products.product')->get() as $option) {
        $bundleProduct = $option->bundle_option_products[0]->product;

        $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => $quantity = 2,
            'value_type'        => 'discount',
            'value'             => rand(20, 50),
            'product_id'        => $bundleProduct->id,
            'customer_group_id' => 3,
        ]);

        $bundleOptions['bundle_options'][$option->id] = [$option->id];

        $bundleOptions['bundle_option_quantities'][$option->id] = $quantity;

        $bundleOptions['grand_total'][] = ($bundleProduct->price - ($bundleProduct->price * $customerGroupPrice->value / 100)) * $customerGroupPrice->qty;
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check customer group price for wholesaler customer with discount price type for bundle product', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'grand_total'              => [],
    ];

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    foreach ($product->bundle_options()->with('bundle_option_products.product')->get() as $option) {
        $bundleProduct = $option->bundle_option_products[0]->product;

        $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => $quantity = 2,
            'value_type'        => 'discount',
            'value'             => rand(20, 50),
            'product_id'        => $bundleProduct->id,
            'customer_group_id' => 3,
        ]);

        $bundleOptions['bundle_options'][$option->id] = [$option->id];

        $bundleOptions['bundle_option_quantities'][$option->id] = $quantity;

        $bundleOptions['grand_total'][] = ($bundleProduct->price - ($bundleProduct->price * $customerGroupPrice->value / 100)) * $customerGroupPrice->qty;
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round(array_sum($bundleOptions['grand_total']), 2), round($response['data']['grand_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for percentage price for bundle product for guest customer into cart', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'prices'                   => [],
    ];

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $bundleOptions['prices'][] = $bundleOption->product->price - ($bundleOption->product->price * ($catalogRule->discount_amount / 100));

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for percentage price for bundle product for guest customer into cart', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'prices'                   => [],
    ];

    $product->load('bundle_options.product');

    foreach ($product->bundle_options as $bundleOption) {
        $bundleOptions['prices'][] = $bundleOption->product->price - ($bundleOption->product->price * ($catalogRule->discount_amount / 100));

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round(array_sum($bundleOptions['prices']), 2), round($response['data']['grand_total'], 2), '', 0.00000001);
    $this->assertEquals(round(array_sum($bundleOptions['prices']), 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for percentage price for bundle product for general customer into cart', function () {
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
    ]))->getBundleProductFactory()->create();

    $product->load('bundle_options.product');

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'prices'                   => [],
    ];

    foreach ($product->bundle_options as $bundleOption) {
        $bundleOptions['prices'][] = $bundleOption->product->price - ($bundleOption->product->price * ($catalogRule->discount_amount / 100));

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for percentage price for bundle product for general customer into cart', function () {
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
    ]))->getBundleProductFactory()->create();

    $product->load('bundle_options.product');

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'prices'                   => [],
    ];

    foreach ($product->bundle_options as $bundleOption) {
        $bundleOptions['prices'][] = $bundleOption->product->price - ($bundleOption->product->price * ($catalogRule->discount_amount / 100));

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round(array_sum($bundleOptions['prices']), 2), round($response['data']['grand_total'], 2), '', 0.00000001);
    $this->assertEquals(round(array_sum($bundleOptions['prices']), 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for percentage price for bundle product for wholesaler customer into cart', function () {
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
    ]))->getBundleProductFactory()->create();

    $product->load('bundle_options.product');

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'prices'                   => [],
    ];

    foreach ($product->bundle_options as $bundleOption) {
        $bundleOptions['prices'][] = $bundleOption->product->price - ($bundleOption->product->price * ($catalogRule->discount_amount / 100));

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for percentage price for bundle product for wholesaler customer into cart', function () {
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
    ]))->getBundleProductFactory()->create();

    $product->load('bundle_options.product');

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'prices'                   => [],
    ];

    foreach ($product->bundle_options as $bundleOption) {
        $bundleOptions['prices'][] = $bundleOption->product->price - ($bundleOption->product->price * ($catalogRule->discount_amount / 100));

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round(array_sum($bundleOptions['prices']), 2), round($response['data']['grand_total'], 2), '', 0.00000001);
    $this->assertEquals(round(array_sum($bundleOptions['prices']), 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for fixed price for bundle product for guest customer into cart', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'prices'                   => [],
    ];

    foreach ($product->bundle_options as $bundleOption) {
        $bundleOptions['prices'][] = $bundleOption->product->price - $catalogRule->discount_amount;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for fixed price for bundle product for guest customer into cart', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'prices'                   => [],
    ];

    foreach ($product->bundle_options as $bundleOption) {
        $bundleOptions['prices'][] = $bundleOption->product->price - $catalogRule->discount_amount;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round(array_sum($bundleOptions['prices']), 2), round($response['data']['grand_total'], 2), '', 0.00000001);
    $this->assertEquals(round(array_sum($bundleOptions['prices']), 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for fixed price for bundle product for general customer into cart', function () {
    // Arrange
    $customer = Customer::factory()->create(['customer_group_id' => 2]);

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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'prices'                   => [],
    ];

    foreach ($product->bundle_options as $bundleOption) {
        $bundleOptions['prices'][] = $bundleOption->product->price - $catalogRule->discount_amount;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for fixed price for bundle product for general customer into cart', function () {
    // Arrange
    $customer = Customer::factory()->create(['customer_group_id' => 2]);

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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'prices'                   => [],
    ];

    foreach ($product->bundle_options as $bundleOption) {
        $bundleOptions['prices'][] = $bundleOption->product->price - $catalogRule->discount_amount;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round(array_sum($bundleOptions['prices']), 2), round($response['data']['grand_total'], 2), '', 0.00000001);
    $this->assertEquals(round(array_sum($bundleOptions['prices']), 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for fixed price for bundle product for wholesaler customer into cart', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'prices'                   => [],
    ];

    foreach ($product->bundle_options as $bundleOption) {
        $bundleOptions['prices'][] = $bundleOption->product->price - $catalogRule->discount_amount;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for fixed price for bundle product for wholesaler customer into cart', function () {
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
    ]))->getBundleProductFactory()->create();

    $bundleOptions = [
        'bundle_option_quantities' => [],
        'bundle_options'           => [],
        'prices'                   => [],
    ];

    foreach ($product->bundle_options as $bundleOption) {
        $bundleOptions['prices'][] = $bundleOption->product->price - $catalogRule->discount_amount;

        $bundleOptions['bundle_option_quantities'][$bundleOption->id] = 1;

        $bundleOptions['bundle_options'][$bundleOption->id] = [$bundleOption->id];
    }

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id'        => $product->id,
        'quantity'          => 1,
        'is_buy_now'        => '0',
        'rating'            => '0',
        'bundle_option_qty' => $bundleOptions['bundle_option_quantities'],
        'bundle_options'    => $bundleOptions['bundle_options'],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertEquals(round(array_sum($bundleOptions['prices']), 2), round($response['data']['grand_total'], 2), '', 0.00000001);
    $this->assertEquals(round(array_sum($bundleOptions['prices']), 2), round($response['data']['sub_total'], 2), '', 0.00000001);
});

it('should check discount price if catalog rule applied for fixed price for bundle product for guest customer', function () {
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
    ]))->getBundleProductFactory()->create();

    // Act and Assert
    foreach ($product->bundle_options as $bundleOption) {
        $this->assertModelWise([
            CatalogRuleProductPrice::class => [
                [
                    'price'             => $bundleOption->product->price - $catalogRule->discount_amount,
                    'customer_group_id' => 1,
                    'catalog_rule_id'   => $catalogRule->id,
                ],
            ],
        ]);
    }
});

it('should check discount price if catalog rule applied for fixed price for bundle product for general customer', function () {
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
    ]))->getBundleProductFactory()->create();

    // Act and Assert
    $this->loginAsCustomer($customer);

    foreach ($product->bundle_options as $bundleOption) {
        $this->assertModelWise([
            CatalogRuleProductPrice::class => [
                [
                    'price'             => $bundleOption->product->price - $catalogRule->discount_amount,
                    'customer_group_id' => 2,
                    'catalog_rule_id'   => $catalogRule->id,
                ],
            ],
        ]);
    }
});

it('should check discount price if catalog rule applied for fixed price for bundle product for wholesaler customer', function () {
    // Arrange
    $customer = Customer::factory()->create();

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
    ]))->getBundleProductFactory()->create();

    // Act and Assert
    $this->loginAsCustomer($customer);

    foreach ($product->bundle_options as $bundleOption) {
        $this->assertModelWise([
            CatalogRuleProductPrice::class => [
                [
                    'price'             => $bundleOption->product->price - $catalogRule->discount_amount,
                    'customer_group_id' => 3,
                    'catalog_rule_id'   => $catalogRule->id,
                ],
            ],
        ]);
    }
});

it('should check discount price if catalog rule applied for percentage price for bundle product for guest customer', function () {
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
    ]))->getBundleProductFactory()->create();

    // Act and Assert
    foreach ($product->bundle_options as $bundleOption) {
        $this->assertModelWise([
            CatalogRuleProductPrice::class => [
                [
                    'price'             => $bundleOption->product->price - ($bundleOption->product->price * ($catalogRule->discount_amount / 100)),
                    'customer_group_id' => 1,
                    'catalog_rule_id'   => $catalogRule->id,
                ],
            ],
        ]);
    }
});

it('should check discount price if catalog rule applied for percentage price for bundle product for general customer', function () {
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
    ]))->getBundleProductFactory()->create();

    // Act and Assert
    $this->loginAsCustomer($customer);

    foreach ($product->bundle_options as $bundleOption) {
        $this->assertModelWise([
            CatalogRuleProductPrice::class => [
                [
                    'price'             => $bundleOption->product->price - ($bundleOption->product->price * ($catalogRule->discount_amount / 100)),
                    'customer_group_id' => 2,
                    'catalog_rule_id'   => $catalogRule->id,
                ],
            ],
        ]);
    }
});

it('should check discount price if catalog rule applied for percentage price for bundle product for wholesaler customer', function () {
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
    ]))->getBundleProductFactory()->create();

    // Act and Assert
    $this->loginAsCustomer($customer);

    foreach ($product->bundle_options as $bundleOption) {
        $this->assertModelWise([
            CatalogRuleProductPrice::class => [
                [
                    'price'             => $bundleOption->product->price - ($bundleOption->product->price * ($catalogRule->discount_amount / 100)),
                    'customer_group_id' => 3,
                    'catalog_rule_id'   => $catalogRule->id,
                ],
            ],
        ]);
    }
});
