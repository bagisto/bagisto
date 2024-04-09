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

it('should fails the validation error when certain inputs not provided when add a grouped product to the cart with a cart rule of the no coupon type for all customer group type', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);
});

it('should add a grouped product to the cart with a cart rule of the no coupon type for all customer group type', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4);

    $this->assertPrice(array_sum($data['prices']) - ($cartRule->discount_amount * 4), $response['data']['grand_total']);

    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount * 4, $response['data']['discount_amount']);

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);
});

it('should fails the validation error when the certain inputs not provided when add a grouped product to the cart with a cart rule of the no coupon type for guest customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);
});

it('should add a grouped product to the cart with a cart rule of the no coupon type for guest customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4);

    $this->assertPrice(array_sum($data['prices']) - ($cartRule->discount_amount * 4), $response['data']['grand_total']);

    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount * 4, $response['data']['discount_amount']);

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);
});

it('should fails the validation error when the certain inputs not provided when add a grouped product to the cart with a cart rule of the no coupon type for general customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $customer = Customer::factory()->create();

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);
});

it('should add a grouped product to the cart with a cart rule of the no coupon type for general customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $customer = Customer::factory()->create();

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4);

    $this->assertPrice(array_sum($data['prices']) - ($cartRule->discount_amount * 4), $response['data']['grand_total']);

    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount * 4, $response['data']['discount_amount']);

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);
});

it('should fails the validation error when the certain inputs not provided when add a grouped product to the cart with a cart rule of the no coupon type for wholesaler customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);
});

it('should add a grouped product to the cart with a cart rule of the no coupon type for wholesaler customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4);

    $this->assertPrice(array_sum($data['prices']) - ($cartRule->discount_amount * 4), $response['data']['grand_total']);

    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount * 4, $response['data']['discount_amount']);
});

it('should fails the validation error when certain inputs not provided when add a grouped product to the cart with a cart rule of the specific coupon type for all customer grouped types', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $cartRuleCoupon = CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    $cart = cart()->addProduct($product, [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.coupon.apply'))
        ->assertJsonValidationErrorFor('code')
        ->assertUnprocessable();

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);

    $this->prepareCartRuleCoupon($cartRuleCoupon);
});

it('should add a grouped product to the cart with a cart rule of the specific coupon type for all customer grouped types', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $cartRuleCoupon = CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    $cart = cart()->addProduct($product, [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.coupon.apply'), [
        'code' => $couponCode,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.coupon.success-apply'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4);

    $this->assertPrice(array_sum($data['prices']) - ($cartRule->discount_amount * 4), $response['data']['grand_total']);

    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount * 4, $response['data']['discount_amount']);

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);

    $this->prepareCartRuleCoupon($cartRuleCoupon);
});

it('should fails the validation error when the certain inputs not provided when add a grouped product to the cart with a cart rule of the specific coupon type for guest customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $cartRuleCoupon = CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    $cart = cart()->addProduct($product, [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.coupon.apply'))
        ->assertJsonValidationErrorFor('code')
        ->assertUnprocessable();

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);

    $this->prepareCartRuleCoupon($cartRuleCoupon);
});

it('should add a grouped product to the cart with a cart rule of the specific coupon type for guest customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $cartRuleCoupon = CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    $cart = cart()->addProduct($product, [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.coupon.apply'), [
        'code' => $couponCode,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.coupon.success-apply'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4);

    $this->assertPrice(array_sum($data['prices']) - ($cartRule->discount_amount * 4), $response['data']['grand_total']);

    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount * 4, $response['data']['discount_amount']);

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);

    $this->prepareCartRuleCoupon($cartRuleCoupon);
});

it('should fails the validation error when the certain inputs not provided when add a grouped product to the cart with a cart rule of the specific coupon type for general customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $cartRuleCoupon = CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities' => [],

        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    $cart = cart()->addProduct($product, [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.coupon.apply'))
        ->assertJsonValidationErrorFor('code')
        ->assertUnprocessable();

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);

    $this->prepareCartRuleCoupon($cartRuleCoupon);
});

it('should add a grouped product to the cart with a cart rule of the specific coupon type for general customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $cartRuleCoupon = CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities' => [],

        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    $cart = cart()->addProduct($product, [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.coupon.apply'), [
        'code' => $couponCode,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.coupon.success-apply'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4);

    $this->assertPrice(array_sum($data['prices']) - ($cartRule->discount_amount * 4), $response['data']['grand_total']);

    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount * 4, $response['data']['discount_amount']);

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);

    $this->prepareCartRuleCoupon($cartRuleCoupon);
});

it('should fails the validation error when the certain inputs not provided when add a grouped product to the cart with a cart rule of the specific coupon type for wholesaler customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $cartRuleCoupon = CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    $cart = cart()->addProduct($product, [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.coupon.apply'))
        ->assertJsonValidationErrorFor('code')
        ->assertUnprocessable();

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);

    $this->prepareCartRuleCoupon($cartRuleCoupon);
});

it('should add a grouped product to the cart with a cart rule of the specific coupon type for wholesaler customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

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

    $cartRuleCoupon = CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code'         => $couponCode = fake()->numerify('bagisto-########'),
        'type'         => 0,
        'is_primary'   => 1,
    ]);

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    $cart = cart()->addProduct($product, [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.coupon.apply'), [
        'code' => $couponCode,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.coupon.success-apply'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4);

    $this->assertPrice(array_sum($data['prices']) - ($cartRule->discount_amount * 4), $response['data']['grand_total']);

    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount * 4, $response['data']['discount_amount']);

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);

    $this->prepareCartRuleCoupon($cartRuleCoupon);
});

it('should check tax is applying for the grouped product into the cart for grouped product', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $customer = Customer::factory()->create();

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities'  => [],
        'prices'      => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = $groupedProduct->associated_product->price * $groupedProduct->qty;
    }

    $cart = cart()->addProduct($product, [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

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

    $response = getJson(route('shop.checkout.onepage.summary'))
        ->assertJsonPath('data.id', $cart->id);

    $this->assertPrice($cart->tax_total, $response['data']['tax_total']);

    $this->assertPrice($cart->base_tax_total, $response['data']['base_tax_total']);

    $this->assertPrice($cart->grand_total, $response['data']['grand_total']);

    $this->assertPrice($cart->sub_total, $response['data']['sub_total']);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for guest customer with fixed price type for grouped product', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    $productCustomerGroupPrices = [];

    foreach ($groupedProducts as $key => $groupedProduct) {
        $productCustomerGroupPrices[$key] = ProductCustomerGroupPrice::factory()->create([
            'qty'               => rand(2, 10),
            'value_type'        => 'fixed',
            'value'             => rand(20, 50),
            'product_id'        => $groupedProduct->associated_product_id,
            'customer_group_id' => 1,
        ]);

        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($productCustomerGroupPrices[$key]->value * $groupedProduct->qty);
    }

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    foreach ($productCustomerGroupPrices as $key => $productCustomerGroupPrice) {
        if (is_null($productCustomerGroupPrice[$key])) {
            continue;
        }

        $this->assertDatabaseHas('product_customer_group_prices', [
            'id'                 => $productCustomerGroupPrice[$key]->id,
            'qty'                => $productCustomerGroupPrice[$key]->qty,
            'value_type'         => 'fixed',
            'value'              => $productCustomerGroupPrice[$key]->value,
            'product_id'         => $productCustomerGroupPrice[$key]->product_id,
            'customer_group_id'  => $productCustomerGroupPrice[$key]->customer_group_id,
        ]);
    }
});

it('should check customer group price for guest customer with fixed price type for grouped product', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    $productCustomerGroupPrices = [];

    foreach ($groupedProducts as $key => $groupedProduct) {
        $productCustomerGroupPrices[$key] = ProductCustomerGroupPrice::factory()->create([
            'qty'               => rand(2, 10),
            'value_type'        => 'fixed',
            'value'             => rand(20, 50),
            'product_id'        => $groupedProduct->associated_product_id,
            'customer_group_id' => 1,
        ]);

        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($productCustomerGroupPrices[$key]->value * $groupedProduct->qty);
    }

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4);

    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);

    $this->assertPrice(array_sum($data['prices']), $response['data']['grand_total']);

    foreach ($productCustomerGroupPrices as $key => $productCustomerGroupPrice) {
        if (is_null($productCustomerGroupPrice[$key])) {
            continue;
        }

        $this->assertDatabaseHas('product_customer_group_prices', [
            'id'                 => $productCustomerGroupPrice[$key]->id,
            'qty'                => $productCustomerGroupPrice[$key]->qty,
            'value_type'         => 'fixed',
            'value'              => $productCustomerGroupPrice[$key]->value,
            'product_id'         => $productCustomerGroupPrice[$key]->product_id,
            'customer_group_id'  => $productCustomerGroupPrice[$key]->customer_group_id,
        ]);
    }
});

it('should fails the validation error when the certain inputs not provided when check customer group price for general customer with fixed price type for grouped product', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $customer = Customer::factory()->create();

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    $productCustomerGroupPrices = [];

    foreach ($groupedProducts as $key => $groupedProduct) {
        $productCustomerGroupPrices[$key] = ProductCustomerGroupPrice::factory()->create([
            'qty'               => rand(2, 10),
            'value_type'        => 'fixed',
            'value'             => rand(20, 50),
            'product_id'        => $groupedProduct->associated_product_id,
            'customer_group_id' => 2,
        ]);

        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($productCustomerGroupPrices[$key]->value * $groupedProduct->qty);
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    foreach ($productCustomerGroupPrices as $key => $productCustomerGroupPrice) {
        if (is_null($productCustomerGroupPrice[$key])) {
            continue;
        }

        $this->assertDatabaseHas('product_customer_group_prices', [
            'id'                 => $productCustomerGroupPrice[$key]->id,
            'qty'                => $productCustomerGroupPrice[$key]->qty,
            'value_type'         => 'fixed',
            'value'              => $productCustomerGroupPrice[$key]->value,
            'product_id'         => $productCustomerGroupPrice[$key]->product_id,
            'customer_group_id'  => $productCustomerGroupPrice[$key]->customer_group_id,
        ]);
    }
});

it('should check customer group price for general customer with fixed price type for grouped product', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $customer = Customer::factory()->create();

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    $productCustomerGroupPrices = [];

    foreach ($groupedProducts as $key => $groupedProduct) {
        $productCustomerGroupPrices[$key] = ProductCustomerGroupPrice::factory()->create([
            'qty'               => rand(2, 10),
            'value_type'        => 'fixed',
            'value'             => rand(20, 50),
            'product_id'        => $groupedProduct->associated_product_id,
            'customer_group_id' => 2,
        ]);

        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($productCustomerGroupPrices[$key]->value * $groupedProduct->qty);
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4);

    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);

    $this->assertPrice(array_sum($data['prices']), $response['data']['grand_total']);

    foreach ($productCustomerGroupPrices as $key => $productCustomerGroupPrice) {
        if (is_null($productCustomerGroupPrice[$key])) {
            continue;
        }

        $this->assertDatabaseHas('product_customer_group_prices', [
            'id'                 => $productCustomerGroupPrice[$key]->id,
            'qty'                => $productCustomerGroupPrice[$key]->qty,
            'value_type'         => 'fixed',
            'value'              => $productCustomerGroupPrice[$key]->value,
            'product_id'         => $productCustomerGroupPrice[$key]->product_id,
            'customer_group_id'  => $productCustomerGroupPrice[$key]->customer_group_id,
        ]);
    }
});

it('should fails the validation error when the certain inputs not provided when check customer group price for wholesaler customer with fixed price type for grouped product', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => rand(2, 10),
            'value_type'        => 'fixed',
            'value'             => rand(20, 50),
            'product_id'        => $groupedProduct->associated_product_id,
            'customer_group_id' => 3,
        ]);

        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($productCustomerPrice->value * $groupedProduct->qty);
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check customer group price for wholesaler customer with fixed price type for grouped product', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $groupedProducts = $product->grouped_products()->with('associated_product')->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => rand(2, 10),
            'value_type'        => 'fixed',
            'value'             => rand(20, 50),
            'product_id'        => $groupedProduct->associated_product_id,
            'customer_group_id' => 3,
        ]);

        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($productCustomerPrice->value * $groupedProduct->qty);
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4);

    $this->assertPrice(array_sum($data['prices']), $response['data']['grand_total']);

    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for guest customer with discount price type for grouped product', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => rand(2, 10),
            'value_type'        => 'discount',
            'value'             => rand(20, 50),
            'product_id'        => $groupedProduct->associated_product_id,
            'customer_group_id' => 1,
        ]);

        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = (($groupedProduct->associated_product->price - ($groupedProduct->associated_product->price * ($productCustomerPrice->value / 100))) * $groupedProduct->qty);
    }

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check customer group price for guest customer with discount price type for grouped product', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => rand(2, 10),
            'value_type'        => 'discount',
            'value'             => rand(20, 50),
            'product_id'        => $groupedProduct->associated_product_id,
            'customer_group_id' => 1,
        ]);

        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = (($groupedProduct->associated_product->price - ($groupedProduct->associated_product->price * ($productCustomerPrice->value / 100))) * $groupedProduct->qty);
    }

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4);

    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);
});

it('should fails the validation error when the certain inputs nor provided when check customer group price for general customer with discount price type for grouped product', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $customer = Customer::factory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => rand(2, 10),
            'value_type'        => 'discount',
            'value'             => rand(20, 50),
            'product_id'        => $groupedProduct->associated_product_id,
            'customer_group_id' => 2,
        ]);

        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = (($groupedProduct->associated_product->price - ($groupedProduct->associated_product->price * ($productCustomerPrice->value / 100))) * $groupedProduct->qty);
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check customer group price for general customer with discount price type for grouped product', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $customer = Customer::factory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => rand(2, 10),
            'value_type'        => 'discount',
            'value'             => rand(20, 50),
            'product_id'        => $groupedProduct->associated_product_id,
            'customer_group_id' => 2,
        ]);

        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = (($groupedProduct->associated_product->price - ($groupedProduct->associated_product->price * ($productCustomerPrice->value / 100))) * $groupedProduct->qty);
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4);

    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for wholesaler customer with discount price type for grouped product', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => rand(2, 10),
            'value_type'        => 'discount',
            'value'             => rand(20, 50),
            'product_id'        => $groupedProduct->associated_product_id,
            'customer_group_id' => 3,
        ]);

        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = (($groupedProduct->associated_product->price - ($groupedProduct->associated_product->price * ($productCustomerPrice->value / 100))) * $groupedProduct->qty);
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check customer group price for wholesaler customer with discount price type for grouped product', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $productCustomerPrice = ProductCustomerGroupPrice::factory()->create([
            'qty'               => rand(2, 10),
            'value_type'        => 'discount',
            'value'             => rand(20, 50),
            'product_id'        => $groupedProduct->associated_product_id,
            'customer_group_id' => 3,
        ]);

        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = (($groupedProduct->associated_product->price - ($groupedProduct->associated_product->price * ($productCustomerPrice->value / 100))) * $groupedProduct->qty);
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => 1,
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']))
        ->assertJsonPath('data.items_count', 4);

    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for percentage price for grouped product for guest customer into cart', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($groupedProduct->associated_product->price - ($groupedProduct->associated_product->price * $catalogRule->discount_amount / 100)) * $groupedProduct->qty;
    }

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => '',
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for percentage price for grouped product for guest customer into cart', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($groupedProduct->associated_product->price - ($groupedProduct->associated_product->price * $catalogRule->discount_amount / 100)) * $groupedProduct->qty;
    }

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => '',
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('data.items_count', 4)
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']));

    $this->assertPrice(array_sum($data['prices']), $response['data']['grand_total']);
    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for percentage price for grouped product for general customer into cart', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($groupedProduct->associated_product->price - ($groupedProduct->associated_product->price * $catalogRule->discount_amount / 100)) * $groupedProduct->qty;
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => '',
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for percentage price for grouped product for general customer into cart', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($groupedProduct->associated_product->price - ($groupedProduct->associated_product->price * $catalogRule->discount_amount / 100)) * $groupedProduct->qty;
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => '',
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('data.items_count', 4)
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']));

    $this->assertPrice(array_sum($data['prices']), $response['data']['grand_total']);
    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for percentage price for grouped product for wholesaler customer into cart', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($groupedProduct->associated_product->price - ($groupedProduct->associated_product->price * $catalogRule->discount_amount / 100)) * $groupedProduct->qty;
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => '',
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for percentage price for grouped product for wholesaler customer into cart', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($groupedProduct->associated_product->price - ($groupedProduct->associated_product->price * $catalogRule->discount_amount / 100)) * $groupedProduct->qty;
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => '',
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('data.items_count', 4)
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']));

    $this->assertPrice(array_sum($data['prices']), $response['data']['grand_total']);
    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for fixed price for grouped product for guest customer into cart', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($groupedProduct->associated_product->price - $catalogRule->discount_amount) * $groupedProduct->qty;
    }

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => '',
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for fixed price for grouped product for guest customer into cart', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($groupedProduct->associated_product->price - $catalogRule->discount_amount) * $groupedProduct->qty;
    }

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => '',
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('data.items_count', 4)
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']));

    $this->assertPrice(array_sum($data['prices']), $response['data']['grand_total']);
    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for fixed price for grouped product for general customer into cart', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($groupedProduct->associated_product->price - $catalogRule->discount_amount) * $groupedProduct->qty;
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => '',
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for fixed price for grouped product for general customer into cart', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($groupedProduct->associated_product->price - $catalogRule->discount_amount) * $groupedProduct->qty;
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => '',
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('data.items_count', 4)
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']));

    $this->assertPrice(array_sum($data['prices']), $response['data']['grand_total']);
    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for fixed price for grouped product for wholesaler customer into cart', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($groupedProduct->associated_product->price - $catalogRule->discount_amount) * $groupedProduct->qty;
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'quantity'   => '',
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();
});

it('should check discount price if catalog rule applied for fixed price for grouped product for wholesaler customer into cart', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    $data = [
        'quantities' => [],
        'prices'     => [],
    ];

    foreach ($groupedProducts as $groupedProduct) {
        $data['quantities'][$groupedProduct->associated_product_id] = $groupedProduct->qty;

        $data['prices'][] = ($groupedProduct->associated_product->price - $catalogRule->discount_amount) * $groupedProduct->qty;
    }

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity'   => '',
        'is_buy_now' => '0',
        'rating'     => '0',
        'qty'        => $data['quantities'],
    ])
        ->assertOk()
        ->assertJsonPath('data.items_count', 4)
        ->assertJsonPath('data.items_qty', array_sum($data['quantities']));

    $this->assertPrice(array_sum($data['prices']), $response['data']['grand_total']);

    $this->assertPrice(array_sum($data['prices']), $response['data']['sub_total']);
});

it('should check discount price if catalog rule applied for fixed price for grouped product for guest customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    foreach ($groupedProducts as $groupedProduct) {
        $this->assertModelWise([
            CatalogRuleProductPrice::class => [
                [
                    'price'             => $groupedProduct->associated_product->price - $catalogRule->discount_amount,
                    'customer_group_id' => 1,
                    'catalog_rule_id'   => $catalogRule->id,
                    'product_id'        => $groupedProduct->associated_product->id,
                ],
            ],
        ]);
    }
});

it('should check discount price if catalog rule applied for fixed price for grouped product for general customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    foreach ($groupedProducts as $groupedProduct) {
        $this->assertModelWise([
            CatalogRuleProductPrice::class => [
                [
                    'price'             => $groupedProduct->associated_product->price - $catalogRule->discount_amount,
                    'customer_group_id' => 2,
                    'catalog_rule_id'   => $catalogRule->id,
                    'product_id'        => $groupedProduct->associated_product->id,
                ],
            ],
        ]);
    }
});

it('should check discount price if catalog rule applied for fixed price for grouped product for wholesaler customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    foreach ($groupedProducts as $groupedProduct) {
        $this->assertModelWise([
            CatalogRuleProductPrice::class => [
                [
                    'price'             => $groupedProduct->associated_product->price - $catalogRule->discount_amount,
                    'customer_group_id' => 3,
                    'catalog_rule_id'   => $catalogRule->id,
                    'product_id'        => $groupedProduct->associated_product->id,
                ],
            ],
        ]);
    }
});

it('should check discount price if catalog rule applied for percentage price for grouped product for guest customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    foreach ($groupedProducts as $groupedProduct) {
        $this->assertModelWise([
            CatalogRuleProductPrice::class => [
                [
                    'price'             => $groupedProduct->associated_product->price - ($groupedProduct->associated_product->price * ($catalogRule->discount_amount / 100)),
                    'customer_group_id' => 1,
                    'catalog_rule_id'   => $catalogRule->id,
                    'product_id'        => $groupedProduct->associated_product->id,
                ],
            ],
        ]);
    }
});

it('should check discount price if catalog rule applied for percentage price for grouped product for general customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    foreach ($groupedProducts as $groupedProduct) {
        $this->assertModelWise([
            CatalogRuleProductPrice::class => [
                [
                    'price'             => $groupedProduct->associated_product->price - ($groupedProduct->associated_product->price * ($catalogRule->discount_amount / 100)),
                    'customer_group_id' => 2,
                    'catalog_rule_id'   => $catalogRule->id,
                    'product_id'        => $groupedProduct->associated_product->id,
                ],
            ],
        ]);
    }
});

it('should check discount price if catalog rule applied for percentage price for grouped product for wholesaler customer', function () {
    // Arrange.
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
    ]))->getGroupedProductFactory()->create();

    $groupedProducts = $product->grouped_products()->with(['associated_product'])->get();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    foreach ($groupedProducts as $groupedProduct) {
        $this->assertModelWise([
            CatalogRuleProductPrice::class => [
                [
                    'price'             => $groupedProduct->associated_product->price - ($groupedProduct->associated_product->price * ($catalogRule->discount_amount / 100)),
                    'customer_group_id' => 3,
                    'catalog_rule_id'   => $catalogRule->id,
                    'product_id'        => $groupedProduct->associated_product->id,
                ],
            ],
        ]);
    }
});
