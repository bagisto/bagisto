<?php

use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\CartRule\Models\CartRuleCustomer;
use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductCustomerGroupPrice;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

afterEach(function () {
    // Clean up the rows which are creating while testing.
    CustomerAddress::query()->delete();
    Customer::query()->delete();
    CartItem::query()->delete();
    Cart::query()->delete();
    Product::query()->delete();
    CartRule::query()->delete();
    CartRuleCoupon::query()->delete();
    TaxRate::query()->delete();
    TaxMap::query()->delete();
    TaxCategory::query()->delete();
    CartRuleCustomer::query()->delete();
});

it('should add a virtual product to the cart with a cart rule of the no coupon type for all customer group type', function () {
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
    ]))->getVirtualProductFactory()->create();

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

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $quantity = rand(1, 10),
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items.0.quantity', $quantity);

    $this->assertEquals(round($product->price, 2), round($response['data']['items'][0]['price'], 2), '', 0.00000001);

    $this->assertEquals(round($cartRule->discount_amount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);

    $this->assertEquals(round(($product->price * $quantity) - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
});

it('should add a virtual product to the cart with a cart rule of the no coupon type for guest customer', function () {
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
    ]))->getVirtualProductFactory()->create();

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

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $quantity = rand(1, 10),
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items.0.quantity', $quantity);

    $this->assertEquals(round($product->price, 2), round($response['data']['items'][0]['price'], 2), '', 0.00000001);

    $this->assertEquals(round($cartRule->discount_amount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);

    $this->assertEquals(round(($product->price * $quantity) - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
});

it('should add a virtual product to the cart with a cart rule of the no coupon type for general customer', function () {
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
    ]))->getVirtualProductFactory()->create();

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

    $customer = Customer::factory()->create(['customer_group_id' => 2]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $quantity = rand(1, 10),
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items.0.quantity', $quantity);

    $this->assertEquals(round($product->price, 2), round($response['data']['items'][0]['price'], 2), '', 0.00000001);

    $this->assertEquals(round($cartRule->discount_amount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);

    $this->assertEquals(round(($product->price * $quantity) - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
});

it('should add a virtual product to the cart with a cart rule of the no coupon type for wholesaler customer', function () {
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
    ]))->getVirtualProductFactory()->create();

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

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $quantity = rand(1, 10),
    ]))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items.0.quantity', $quantity)
        ->assertJsonPath('data.discount_amount', $cartRule->discount_amount);

    $this->assertEquals(round($product->price, 2), round($response['data']['items'][0]['price'], 2), '', 0.00000001);

    $this->assertEquals(round(($product->price * $quantity) - $cartRule->discount_amount, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
});

it('should add a virtual product to the cart with a cart rule of the specific coupon type for all customer groupd types', function () {
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
    ]))->getVirtualProductFactory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1, 2, 3]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => $discountAmount = rand(1, 50),
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

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_email'      => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
    ]);

    CartItem::factory()->create([
        'product_id' => $product->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
        'cart_id'    => $cart->id,
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

    $this->assertEquals(round($product->price, 2), round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($discountAmount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should add a virtual product to the cart with a cart rule of the specific coupon type for guest customer', function () {
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
    ]))->getVirtualProductFactory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([1]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => $discountAmount = rand(1, 50),
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

    $cart = Cart::factory()->create([
        'customer_email'      => fake()->email(),
        'customer_first_name' => fake()->firstName(),
        'customer_last_name'  => fake()->lastName(),
    ]);

    CartItem::factory()->create([
        'product_id' => $product->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
        'cart_id'    => $cart->id,
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

    $this->assertEquals(round($product->price, 2), round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($discountAmount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should add a virtual product to the cart with a cart rule of the specific coupon type for general customer', function () {
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
    ]))->getVirtualProductFactory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([2]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => $discountAmount = rand(1, 50),
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

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_email'      => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
    ]);

    CartItem::factory()->create([
        'product_id' => $product->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
        'cart_id'    => $cart->id,
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

    $this->assertEquals(round($product->price, 2), round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($discountAmount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should add a virtual product to the cart with a cart rule of the specific coupon type for wholesaler customer', function () {
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
    ]))->getVirtualProductFactory()->create();

    $cartRule = CartRule::factory()->afterCreating(function (CartRule $cartRule) {
        $cartRule->cart_rule_customer_groups()->sync([3]);

        $cartRule->cart_rule_channels()->sync([1]);
    })->create([
        'name'                      => fake()->uuid(),
        'description'               => fake()->sentence(),
        'action_type'               => 'by_fixed',
        'discount_amount'           => $discountAmount = rand(1, 50),
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

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_email'      => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
    ]);

    CartItem::factory()->create([
        'product_id' => $product->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
        'cart_id'    => $cart->id,
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

    $this->assertEquals(round($product->price, 2), round($response['data']['sub_total'], 2), '', 0.00000001);

    $this->assertEquals(round($discountAmount, 2), round($response['data']['discount_amount'], 2), '', 0.00000001);
});

it('should check tax is appling for the virtual product into the cart for virtual product', function () {
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
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getVirtualProductFactory()->create();

    $customer = Customer::factory()->create();

    $cart = Cart::factory()->create([
        'customer_id'         => $customer->id,
        'customer_email'      => $customer->email,
        'customer_first_name' => $customer->first_name,
        'customer_last_name'  => $customer->last_name,
    ]);

    CartItem::factory()->create([
        'product_id' => $product->id,
        'sku'        => $product->sku,
        'type'       => $product->type,
        'name'       => $product->name,
        'cart_id'    => $cart->id,
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
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.sub_total', round($product->price, 2))
        ->assertJsonPath('data.tax_total', round($cart->tax_total, 2))
        ->assertJsonPath('data.base_tax_total', round($cart->base_tax_total, 2))
        ->assertJsonPath('data.grand_total', round($cart->grand_total, 2), '', 0.00000001);
});

it('should check customer group price for guest customer with fixed price type for virtual product', function () {
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
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getVirtualProductFactory()->create();

    $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(2, 5),
        'value_type'        => 'fixed',
        'value'             => rand(20, 50),
        'product_id'        => $product->id,
        'customer_group_id' => 1,
    ]);

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $customerGroupPrice->qty,
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', $customerGroupPrice->qty)
        ->assertJsonPath('data.items_qty', $customerGroupPrice->qty);

    $this->assertEquals(round($customerGroupPrice->value * $customerGroupPrice->qty, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
});

it('should check customer group price for guest customer with discount price type for virtual product', function () {
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
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
            'guest_checkout' => [
                'boolean_value' => true,
            ],
        ],
    ]))->getVirtualProductFactory()->create();

    $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(2, 5),
        'value_type'        => 'discount',
        'value'             => rand(20, 50),
        'product_id'        => $product->id,
        'customer_group_id' => 1,
    ]);

    $totalAmount = $product->price * $customerGroupPrice->qty;

    $grandTotal = ($totalAmount - ($totalAmount * ($customerGroupPrice->value / 100)));

    // Act and Assert
    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $customerGroupPrice->qty,
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', $customerGroupPrice->qty);

    $this->assertEquals(round($grandTotal, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
});

it('should check customer group price for general customer with fixed price type for virtual product', function () {
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
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
        ],
    ]))->getVirtualProductFactory()->create();

    $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(2, 5),
        'value_type'        => 'fixed',
        'value'             => rand(20, 50),
        'product_id'        => $product->id,
        'customer_group_id' => 2,
    ]);

    $customer = Customer::factory()->create();

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $customerGroupPrice->qty,
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', $customerGroupPrice->qty)
        ->assertJsonPath('data.items_qty', $customerGroupPrice->qty);

    $this->assertEquals($customerGroupPrice->value * $customerGroupPrice->qty, round($response['data']['grand_total'], 2), '', 0.00000001);
});

it('should check customer group price for general customer with discount price type for virtual product', function () {
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
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
        ],
    ]))->getVirtualProductFactory()->create();

    $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(2, 5),
        'value_type'        => 'discount',
        'value'             => rand(20, 50),
        'product_id'        => $product->id,
        'customer_group_id' => 2,
    ]);

    $customer = Customer::factory()->create();

    $totalAmount = $product->price * $customerGroupPrice->qty;

    $grandTotal = ($totalAmount - ($totalAmount * ($customerGroupPrice->value / 100)));

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $customerGroupPrice->qty,
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', $customerGroupPrice->qty);

    $this->assertEquals(round($grandTotal, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
});

it('should check customer group price for wholesale customer with fixed price type for virtual product', function () {
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
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
        ],
    ]))->getVirtualProductFactory()->create();

    $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(2, 5),
        'value_type'        => 'fixed',
        'value'             => rand(20, 50),
        'product_id'        => $product->id,
        'customer_group_id' => 3,
    ]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $customerGroupPrice->qty,
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.quantity', $customerGroupPrice->qty)
        ->assertJsonPath('data.items_qty', $customerGroupPrice->qty);

    $this->assertEquals(round($customerGroupPrice->value * $customerGroupPrice->qty, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
});

it('should check customer group price for wholesale customer with discount price type for virtual product', function () {
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
                'float_value' => fake()->randomFloat(2, 1000, 5000),
            ],
        ],
    ]))->getVirtualProductFactory()->create();

    $customerGroupPrice = ProductCustomerGroupPrice::factory()->create([
        'qty'               => rand(2, 5),
        'value_type'        => 'discount',
        'value'             => rand(20, 50),
        'product_id'        => $product->id,
        'customer_group_id' => 3,
    ]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $totalAmount = $product->price * $customerGroupPrice->qty;

    $grandTotal = ($totalAmount - ($totalAmount * ($customerGroupPrice->value / 100)));

    // Act and Assert
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'product_id' => $product->id,
        'quantity'   => $customerGroupPrice->qty,
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', $customerGroupPrice->qty);

    $this->assertEquals(round($grandTotal, 2), round($response['data']['grand_total'], 2), '', 0.00000001);
});
