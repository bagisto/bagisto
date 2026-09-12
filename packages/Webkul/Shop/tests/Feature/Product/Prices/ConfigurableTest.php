<?php

use Illuminate\Support\Facades\Event;
use Webkul\Attribute\Models\Attribute;
use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\CatalogRule\Models\CatalogRuleProductPrice;
use Webkul\CatalogRule\Repositories\CatalogRuleRepository;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Helpers\Indexers\Price as PriceIndexer;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductCustomerGroupPrice;
use Webkul\Product\Models\ProductPriceIndex;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

/**
 * Create a category a rule condition can name.
 */
function createRuleConditionCategory(): Category
{
    return Category::factory()
        ->has(CategoryTranslation::factory(), 'translations')
        ->create();
}

/**
 * Create a catalog rule taking 20% off the products whose categories meet the operator for the category.
 */
function createCategoryConditionCatalogRule($test, string $operator, Category $category): CatalogRule
{
    return $test->createCatalogRuleForPricing([
        'action_type' => 'by_percent',
        'discount_amount' => 20,
        'condition_type' => 1,
        'conditions' => [[
            'attribute' => 'product|category_ids',
            'operator' => $operator,
            'value' => [(string) $category->id],
            'attribute_type' => 'multiselect',
        ]],
    ], [1, 2, 3]);
}

/**
 * The price a guest sees a product listed at.
 */
function guestListedPrice(Product $product): float
{
    return (float) $product->fresh()->getTypeInstance()->getMinimalPrice();
}

it('should fails the validation error when the certain inputs not provided when add a configurable product to the cart with a cart rule of the no coupon type for all customer group type', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(1, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
        'use_auto_generation' => 0,
        'times_used' => 0,
        'coupon_type' => 0,
        'end_other_rules' => 0,
        'uses_attribute_conditions' => 0,
        'discount_step' => 0,
        'free_shipping' => 0,
        'sort_order' => 0,
        'conditions' => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from' => null,
        'ends_till' => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
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

it('should add a configurable product to the cart with a cart rule of the no coupon type for all customer group type', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(1, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
        'use_auto_generation' => 0,
        'times_used' => 0,
        'coupon_type' => 0,
        'end_other_rules' => 0,
        'uses_attribute_conditions' => 0,
        'discount_step' => 0,
        'free_shipping' => 0,
        'sort_order' => 0,
        'conditions' => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from' => null,
        'ends_till' => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertPrice($childProduct->price - $cartRule->discount_amount, $response['data']['grand_total']);

    $this->assertPrice($childProduct->price, $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount, $response['data']['discount_amount']);

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);
});

it('should fails the validation error when the certain inputs not provided when add a configurable product to the cart with a cart rule of the no coupon type for guest customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(1, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
        'use_auto_generation' => 0,
        'times_used' => 0,
        'coupon_type' => 0,
        'end_other_rules' => 0,
        'uses_attribute_conditions' => 0,
        'discount_step' => 0,
        'free_shipping' => 0,
        'sort_order' => 0,
        'conditions' => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from' => null,
        'ends_till' => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
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

it('should add a configurable product to the cart with a cart rule of the no coupon type for guest customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(1, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
        'use_auto_generation' => 0,
        'times_used' => 0,
        'coupon_type' => 0,
        'end_other_rules' => 0,
        'uses_attribute_conditions' => 0,
        'discount_step' => 0,
        'free_shipping' => 0,
        'sort_order' => 0,
        'conditions' => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from' => null,
        'ends_till' => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertPrice($childProduct->price - $cartRule->discount_amount, $response['data']['grand_total']);

    $this->assertPrice($childProduct->price, $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount, $response['data']['discount_amount']);

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);
});

it('should fails the validation error when certain inputs not provided when add a configurable product to the cart with a cart rule of the no coupon type for general customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(1, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
        'use_auto_generation' => 0,
        'times_used' => 0,
        'coupon_type' => 0,
        'end_other_rules' => 0,
        'uses_attribute_conditions' => 0,
        'discount_step' => 0,
        'free_shipping' => 0,
        'sort_order' => 0,
        'conditions' => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from' => null,
        'ends_till' => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
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

it('should add a configurable product to the cart with a cart rule of the no coupon type for general customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(1, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
        'use_auto_generation' => 0,
        'times_used' => 0,
        'coupon_type' => 0,
        'end_other_rules' => 0,
        'uses_attribute_conditions' => 0,
        'discount_step' => 0,
        'free_shipping' => 0,
        'sort_order' => 0,
        'conditions' => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from' => null,
        'ends_till' => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertPrice($childProduct->price - $cartRule->discount_amount, $response['data']['grand_total']);

    $this->assertPrice($childProduct->price, $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount, $response['data']['discount_amount']);

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);
});

it('should fails the validation error when the certain inputs not provided when add a configurable product to the cart with a cart rule of the no coupon type for wholesaler customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(1, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
        'use_auto_generation' => 0,
        'times_used' => 0,
        'coupon_type' => 0,
        'end_other_rules' => 0,
        'uses_attribute_conditions' => 0,
        'discount_step' => 0,
        'free_shipping' => 0,
        'sort_order' => 0,
        'conditions' => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from' => null,
        'ends_till' => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
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

it('should add a configurable product to the cart with a cart rule of the no coupon type for wholesaler customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(1, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
        'use_auto_generation' => 0,
        'times_used' => 0,
        'coupon_type' => 0,
        'end_other_rules' => 0,
        'uses_attribute_conditions' => 0,
        'discount_step' => 0,
        'free_shipping' => 0,
        'sort_order' => 0,
        'conditions' => json_decode('[{"value": "20000", "operator": "<=", "attribute": "cart_item|base_price", "attribute_type": "price"}]'),
        'starts_from' => null,
        'ends_till' => null,
    ]);

    $childProduct = $product->variants()->first();

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertPrice($childProduct->price - $cartRule->discount_amount, $response['data']['grand_total']);

    $this->assertPrice($childProduct->price, $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount, $response['data']['discount_amount']);

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);
});

it('should fails the validation error when the certain inputs not provided when add a configurable product to the cart with a cart rule of the specific coupon type for all customer grouped types', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(20, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
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

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product, [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
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

it('should add a configurable product to the cart with a cart rule of the specific coupon type for all customer grouped types', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(20, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
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

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product, [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.coupon.apply'), [
        'code' => $couponCode,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.coupon.success-apply'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertPrice($childProduct->price - $cartRule->discount_amount, $response['data']['grand_total']);

    $this->assertPrice($childProduct->price, $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount, $response['data']['discount_amount']);

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);

    $this->prepareCartRuleCoupon($cartRuleCoupon);
});

it('should fails the validation error when certain inputs not provided when add a configurable product to the cart with a cart rule of the specific coupon type for guest customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(20, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
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

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product, [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
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

it('should add a configurable product to the cart with a cart rule of the specific coupon type for guest customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(20, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
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

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product, [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.coupon.apply'), [
        'code' => $couponCode,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.coupon.success-apply'))
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertPrice($childProduct->price - $cartRule->discount_amount, $response['data']['grand_total']);

    $this->assertPrice($childProduct->price, $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount, $response['data']['discount_amount']);

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);

    $this->prepareCartRuleCoupon($cartRuleCoupon);
});

it('should fails the validation error when the certain inputs not provided when  add a configurable product to the cart with a cart rule of the specific coupon type for general customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(20, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
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
        'code' => fake()->numerify('bagisto-########'),
        'type' => 0,
        'is_primary' => 1,
    ]);

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product, [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
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

it('should add a configurable product to the cart with a cart rule of the specific coupon type for general customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(20, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
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

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product, [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
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
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertPrice($childProduct->price - $cartRule->discount_amount, $response['data']['grand_total']);

    $this->assertPrice($childProduct->price, $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount, $response['data']['discount_amount']);

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);

    $this->prepareCartRuleCoupon($cartRuleCoupon);
});

it('should fails the validation error when the certain input not provided when add a configurable product to the cart with a cart rule of the specific coupon type for wholesaler customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(20, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
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

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product, [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
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

it('should add a configurable product to the cart with a cart rule of the specific coupon type for wholesaler customer', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'name' => fake()->uuid(),
        'description' => fake()->sentence(),
        'action_type' => 'by_fixed',
        'discount_amount' => rand(20, 50),
        'usage_per_customer' => rand(1, 50),
        'uses_per_coupon' => rand(1, 50),
        'condition_type' => 2,
        'status' => 1,
        'discount_quantity' => 1,
        'apply_to_shipping' => 1,
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

    $childProduct = $product->variants()->first();

    $cart = cart()->addProduct($product, [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
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
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.items_count', 1);

    $this->assertPrice($childProduct->price - $cartRule->discount_amount, $response['data']['grand_total']);

    $this->assertPrice($childProduct->price, $response['data']['sub_total']);

    $this->assertPrice($cartRule->discount_amount, $response['data']['discount_amount']);

    $this->assertModelWise([
        CartRule::class => [
            $this->prepareCartRule($cartRule),
        ],
    ]);

    $this->prepareCartRuleCustomerGroup($cartRule);

    $this->prepareCartRuleChannel($cartRule);

    $this->prepareCartRuleCoupon($cartRuleCoupon);
});

it('should check tax is applying for the configurable product into the cart for configurable product', function () {
    // Arrange.
    $taxCategory = TaxCategory::factory()->create();

    $taxRate = TaxRate::factory()->create([
        'zip_code' => '',
        'country' => $countryCode = 'IN',
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

    $cart = cart()->addProduct($product, [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ]);

    cart()->setCart($cart);

    cart()->collectTotals();

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

    cart()->collectTotals();

    $cart->refresh();

    $response = getJson(route('shop.checkout.onepage.summary'))
        ->assertJsonPath('data.id', $cart->id);

    $this->assertPrice($cart->tax_total, $response['data']['tax_total']);

    $this->assertPrice($cart->grand_total, $response['data']['grand_total']);

    $this->assertPrice($cart->sub_total, $response['data']['sub_total']);
});

it('should check customer group price for guest customer with fixed price type for configurable product', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'qty' => rand(5, 10),
        'value_type' => 'fixed',
        'value' => rand(20, 50),
        'product_id' => $childProduct->id,
        'customer_group_id' => 1,
    ]);

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => $productCustomerPrice->qty,
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $productCustomerPrice->qty)
        ->assertJsonPath('data.items_count', 1);

    $this->assertPrice($productCustomerPrice->value * $productCustomerPrice->qty, $response['data']['grand_total']);

    $this->assertPrice($productCustomerPrice->value * $productCustomerPrice->qty, $response['data']['sub_total']);

    $this->assertDatabaseHas('product_customer_group_prices', [
        'qty' => $productCustomerPrice->qty,
        'value_type' => $productCustomerPrice->value_type,
        'value' => $productCustomerPrice->value,
        'product_id' => $productCustomerPrice->product_id,
        'customer_group_id' => $productCustomerPrice->customer_group_id,
    ]);
});

it('should fails the validation error when the certain input not provided when check customer group price for general customer with fixed price type for configurable product', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'qty' => rand(5, 10),
        'value_type' => 'fixed',
        'value' => rand(20, 50),
        'product_id' => $childProduct->id,
        'customer_group_id' => 2,
    ]);

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => $productCustomerPrice->qty,
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    $this->assertDatabaseHas('product_customer_group_prices', [
        'qty' => $productCustomerPrice->qty,
        'value_type' => $productCustomerPrice->value_type,
        'value' => $productCustomerPrice->value,
        'product_id' => $productCustomerPrice->product_id,
        'customer_group_id' => $productCustomerPrice->customer_group_id,
    ]);
});

it('should check customer group price for general customer with fixed price type for configurable product', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'qty' => rand(5, 10),
        'value_type' => 'fixed',
        'value' => rand(20, 50),
        'product_id' => $childProduct->id,
        'customer_group_id' => 2,
    ]);

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => $productCustomerPrice->qty,
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $productCustomerPrice->qty)
        ->assertJsonPath('data.items_count', 1);

    $this->assertPrice($productCustomerPrice->value * $productCustomerPrice->qty, $response['data']['grand_total']);

    $this->assertPrice($productCustomerPrice->value * $productCustomerPrice->qty, $response['data']['sub_total']);

    $this->assertDatabaseHas('product_customer_group_prices', [
        'qty' => $productCustomerPrice->qty,
        'value_type' => $productCustomerPrice->value_type,
        'value' => $productCustomerPrice->value,
        'product_id' => $productCustomerPrice->product_id,
        'customer_group_id' => $productCustomerPrice->customer_group_id,
    ]);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for wholesaler customer with fixed price type for configurable product', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'qty' => rand(5, 10),
        'value_type' => 'fixed',
        'value' => rand(20, 50),
        'product_id' => $childProduct->id,
        'customer_group_id' => 3,
    ]);

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => $productCustomerPrice->qty,
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    $this->assertDatabaseHas('product_customer_group_prices', [
        'qty' => $productCustomerPrice->qty,
        'value_type' => $productCustomerPrice->value_type,
        'value' => $productCustomerPrice->value,
        'product_id' => $productCustomerPrice->product_id,
        'customer_group_id' => $productCustomerPrice->customer_group_id,
    ]);
});

it('should check customer group price for wholesaler customer with fixed price type for configurable product', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'qty' => rand(5, 10),
        'value_type' => 'fixed',
        'value' => rand(20, 50),
        'product_id' => $childProduct->id,
        'customer_group_id' => 3,
    ]);

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => $productCustomerPrice->qty,
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $productCustomerPrice->qty)
        ->assertJsonPath('data.items_count', 1);

    $this->assertPrice($productCustomerPrice->value * $productCustomerPrice->qty, $response['data']['grand_total']);

    $this->assertPrice($productCustomerPrice->value * $productCustomerPrice->qty, $response['data']['sub_total']);

    $this->assertDatabaseHas('product_customer_group_prices', [
        'qty' => $productCustomerPrice->qty,
        'value_type' => $productCustomerPrice->value_type,
        'value' => $productCustomerPrice->value,
        'product_id' => $productCustomerPrice->product_id,
        'customer_group_id' => $productCustomerPrice->customer_group_id,
    ]);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for guest customer with discount price type for configurable product', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'qty' => rand(5, 10),
        'value_type' => 'discount',
        'value' => rand(20, 50),
        'product_id' => $childProduct->id,
        'customer_group_id' => 1,
    ]);

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => $productCustomerPrice->qty,
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    $this->assertDatabaseHas('product_customer_group_prices', [
        'qty' => $productCustomerPrice->qty,
        'value_type' => $productCustomerPrice->value_type,
        'value' => $productCustomerPrice->value,
        'product_id' => $productCustomerPrice->product_id,
        'customer_group_id' => $productCustomerPrice->customer_group_id,
    ]);
});

it('should check customer group price for guest customer with discount price type for configurable product', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'qty' => rand(5, 10),
        'value_type' => 'discount',
        'value' => rand(20, 50),
        'product_id' => $childProduct->id,
        'customer_group_id' => 1,
    ]);

    $grandTotal = ($childProduct->price - ($childProduct->price * $productCustomerPrice->value / 100)) * $productCustomerPrice->qty;

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => $productCustomerPrice->qty,
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $productCustomerPrice->qty)
        ->assertJsonPath('data.items_count', 1);

    $this->assertPrice($grandTotal, $response['data']['grand_total']);

    $this->assertPrice($grandTotal, $response['data']['sub_total']);

    $this->assertDatabaseHas('product_customer_group_prices', [
        'qty' => $productCustomerPrice->qty,
        'value_type' => $productCustomerPrice->value_type,
        'value' => $productCustomerPrice->value,
        'product_id' => $productCustomerPrice->product_id,
        'customer_group_id' => $productCustomerPrice->customer_group_id,
    ]);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for general customer with discount price type for configurable product', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'qty' => rand(5, 10),
        'value_type' => 'discount',
        'value' => rand(20, 50),
        'product_id' => $childProduct->id,
        'customer_group_id' => 2,
    ]);

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => $productCustomerPrice->qty,
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    $this->assertDatabaseHas('product_customer_group_prices', [
        'qty' => $productCustomerPrice->qty,
        'value_type' => $productCustomerPrice->value_type,
        'value' => $productCustomerPrice->value,
        'product_id' => $productCustomerPrice->product_id,
        'customer_group_id' => $productCustomerPrice->customer_group_id,
    ]);
});

it('should check customer group price for general customer with discount price type for configurable product', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'qty' => rand(5, 10),
        'value_type' => 'discount',
        'value' => rand(20, 50),
        'product_id' => $childProduct->id,
        'customer_group_id' => 2,
    ]);

    $grandTotal = (($childProduct->price - ($childProduct->price * ($productCustomerPrice->value / 100))) * $productCustomerPrice->qty);

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => $productCustomerPrice->qty,
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $productCustomerPrice->qty)
        ->assertJsonPath('data.items_count', 1);

    $this->assertPrice($grandTotal, $response['data']['grand_total']);

    $this->assertPrice($grandTotal, $response['data']['sub_total']);

    $this->assertDatabaseHas('product_customer_group_prices', [
        'qty' => $productCustomerPrice->qty,
        'value_type' => $productCustomerPrice->value_type,
        'value' => $productCustomerPrice->value,
        'product_id' => $productCustomerPrice->product_id,
        'customer_group_id' => $productCustomerPrice->customer_group_id,
    ]);
});

it('should fails the validation error when the certain inputs not provided when check customer group price for wholesaler customer with discount price type for configurable product', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'qty' => rand(5, 10),
        'value_type' => 'discount',
        'value' => rand(20, 50),
        'product_id' => $childProduct->id,
        'customer_group_id' => 3,
    ]);

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => $productCustomerPrice->qty,
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    $this->assertDatabaseHas('product_customer_group_prices', [
        'qty' => $productCustomerPrice->qty,
        'value_type' => $productCustomerPrice->value_type,
        'value' => $productCustomerPrice->value,
        'product_id' => $productCustomerPrice->product_id,
        'customer_group_id' => $productCustomerPrice->customer_group_id,
    ]);
});

it('should check customer group price for wholesaler customer with discount price type for configurable product', function () {
    // Arrange.
    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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
        'qty' => rand(5, 10),
        'value_type' => 'discount',
        'value' => rand(20, 50),
        'product_id' => $childProduct->id,
        'customer_group_id' => 3,
    ]);

    $grandTotal = (($childProduct->price - ($childProduct->price * ($productCustomerPrice->value / 100))) * $productCustomerPrice->qty);

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store'), [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => $productCustomerPrice->qty,
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_qty', $productCustomerPrice->qty)
        ->assertJsonPath('data.items_count', 1);

    $this->assertPrice($grandTotal, $response['data']['grand_total']);

    $this->assertPrice($grandTotal, $response['data']['sub_total']);

    $this->assertDatabaseHas('product_customer_group_prices', [
        'qty' => $productCustomerPrice->qty,
        'value_type' => $productCustomerPrice->value_type,
        'value' => $productCustomerPrice->value,
        'product_id' => $productCustomerPrice->product_id,
        'customer_group_id' => $productCustomerPrice->customer_group_id,
    ]);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for percentage price for configurable product for guest customer into cart', function () {
    // Arrange.
    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([1]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should check discount price if catalog rule applied for percentage price for configurable product for guest customer into cart', function () {
    // Arrange.
    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([1]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertPrice($grandTotal, $response['data']['grand_total']);

    $this->assertPrice($grandTotal, $response['data']['sub_total']);

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for percentage price for configurable product for general customer into cart', function () {
    // Arrange.
    $customer = Customer::factory()->create();

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([2]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should check discount price if catalog rule applied for percentage price for configurable product for general customer into cart', function () {
    // Arrange.
    $customer = Customer::factory()->create();

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([2]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertPrice($grandTotal, $response['data']['grand_total']);

    $this->assertPrice($grandTotal, $response['data']['sub_total']);

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for percentage price for configurable product for wholesaler customer into cart', function () {
    // Arrange.
    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([3]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should check discount price if catalog rule applied for percentage price for configurable product for wholesaler customer into cart', function () {
    // Arrange.
    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([3]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertPrice($grandTotal, $response['data']['grand_total']);

    $this->assertPrice($grandTotal, $response['data']['sub_total']);

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should fails the validation error when the certain inputs not provided check discount price if catalog rule applied for fixed price for configurable product for guest customer into cart', function () {
    // Arrange.
    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([1]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should check discount price if catalog rule applied for fixed price for configurable product for guest customer into cart', function () {
    // Arrange.
    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([1]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $response = postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertPrice($childProduct->price - $catalogRule->discount_amount, $response['data']['grand_total']);

    $this->assertPrice($childProduct->price - $catalogRule->discount_amount, $response['data']['sub_total']);

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for fixed price for configurable product for general customer into cart', function () {
    // Arrange.
    $customer = Customer::factory()->create();

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([2]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should check discount price if catalog rule applied for fixed price for configurable product for general customer into cart', function () {
    // Arrange.
    $customer = Customer::factory()->create();

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([2]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertPrice($childProduct->price - $catalogRule->discount_amount, $response['data']['grand_total']);

    $this->assertPrice($childProduct->price - $catalogRule->discount_amount, $response['data']['sub_total']);

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should fails the validation error when the certain inputs not provided when check discount price if catalog rule applied for fixed price for configurable product for wholesaler customer into cart', function () {
    // Arrange.
    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([3]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $this->loginAsCustomer($customer);

    postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertJsonValidationErrorFor('product_id')
        ->assertUnprocessable();

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should check discount price if catalog rule applied for fixed price for configurable product for wholesaler customer into cart', function () {
    // Arrange.
    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([3]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $response = postJson(route('shop.api.checkout.cart.store', [
        'selected_configurable_option' => $childProduct->id,
        'product_id' => $product->id,
        'is_buy_now' => '0',
        'rating' => '0',
        'quantity' => '1',
        'super_attribute' => [
            23 => '1',
            24 => '7',
        ],
    ]))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1);

    $this->assertPrice($childProduct->price - $catalogRule->discount_amount, $response['data']['grand_total']);

    $this->assertPrice($childProduct->price - $catalogRule->discount_amount, $response['data']['sub_total']);

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should check discount price if catalog rule applied for fixed price for configurable product for guest customer', function () {
    // Arrange.
    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([1]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $this->assertModelWise([
        CatalogRuleProductPrice::class => [
            [
                'price' => $childProduct->price - $catalogRule->discount_amount,
                'customer_group_id' => 1,
                'catalog_rule_id' => $catalogRule->id,
                'product_id' => $childProduct->id,
            ],
        ],
    ]);

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should check discount price if catalog rule applied for fixed price for configurable product for general customer', function () {
    // Arrange.
    $customer = Customer::factory()->create();

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([2]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $this->assertModelWise([
        CatalogRuleProductPrice::class => [
            [
                'price' => $childProduct->price - $catalogRule->discount_amount,
                'customer_group_id' => 2,
                'catalog_rule_id' => $catalogRule->id,
                'product_id' => $childProduct->id,
            ],
        ],
    ]);

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should check discount price if catalog rule applied for fixed price for configurable product for wholesaler customer', function () {
    // Arrange.
    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([3]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
        'action_type' => 'by_fixed',
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $this->assertModelWise([
        CatalogRuleProductPrice::class => [
            [
                'price' => $childProduct->price - $catalogRule->discount_amount,
                'customer_group_id' => 3,
                'catalog_rule_id' => $catalogRule->id,
                'product_id' => $childProduct->id,
            ],
        ],
    ]);

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should check discount price if catalog rule applied for percentage price for configurable product for guest customer', function () {
    // Arrange.
    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([1]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $this->assertModelWise([
        CatalogRuleProductPrice::class => [
            [
                'price' => $childProduct->price - ($childProduct->price * ($catalogRule->discount_amount / 100)),
                'customer_group_id' => 1,
                'catalog_rule_id' => $catalogRule->id,
                'product_id' => $childProduct->id,
            ],
        ],
    ]);

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should check discount price if catalog rule applied for percentage price for configurable product for general customer', function () {
    // Arrange.
    $customer = Customer::factory()->create();

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([2]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $this->assertModelWise([
        CatalogRuleProductPrice::class => [
            [
                'price' => $childProduct->price - ($childProduct->price * ($catalogRule->discount_amount / 100)),
                'customer_group_id' => 2,
                'catalog_rule_id' => $catalogRule->id,
                'product_id' => $childProduct->id,
            ],
        ],
    ]);

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should check discount price if catalog rule applied for percentage price for configurable product for wholesaler customer', function () {
    // Arrange.
    $customer = Customer::factory()->create(['customer_group_id' => 3]);

    $catalogRule = CatalogRule::factory()->afterCreating(function (CatalogRule $catalogRule) {
        $catalogRule->channels()->sync([1]);

        $catalogRule->customer_groups()->sync([3]);
    })->create([
        'status' => 1,
        'sort_order' => 1,
    ]);

    $product = (new ProductFaker([
        'attributes' => [
            5 => 'new',
            6 => 'featured',
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

    // Act and Assert.
    $this->loginAsCustomer($customer);

    $this->assertModelWise([
        CatalogRuleProductPrice::class => [
            [
                'price' => $childProduct->price - ($childProduct->price * ($catalogRule->discount_amount / 100)),
                'customer_group_id' => 3,
                'catalog_rule_id' => $catalogRule->id,
                'product_id' => $childProduct->id,
            ],
        ],
    ]);

    $this->prepareCatalogRule($catalogRule);

    $this->prepareCatalogRuleChannel($catalogRule);

    $this->prepareCatalogRuleCustomerGroup($catalogRule);
});

it('should reprice the configurable product as soon as a catalog rule discounts its variants', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    expect((float) $product->fresh()->getTypeInstance()->getMinimalPrice())->toBe(800.0);
});

it('should restore the configurable product price as soon as the catalog rule is deleted', function () {
    $product = $this->createConfigurableProduct([1000]);

    $catalogRule = $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    ProductPriceIndex::where('product_id', $product->id)->update(['min_price' => 800]);

    Event::dispatch('promotions.catalog_rule.delete.before', $catalogRule->id);

    app(CatalogRuleRepository::class)->delete($catalogRule->id);

    expect((float) $product->fresh()->getTypeInstance()->getMinimalPrice())->toBe(1000.0);
});

it('should reprice the configurable product when the nightly price reindex reprices its variants', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    ProductPriceIndex::where('product_id', $product->id)->update(['min_price' => 1000]);

    app(PriceIndexer::class)->reindexSelective();

    expect((float) $product->fresh()->getTypeInstance()->getMinimalPrice())->toBe(800.0);
});

it('should leave out the variants of a configurable product in a category a does not contain condition excludes', function () {
    $footwear = createRuleConditionCategory();

    $product = $this->createConfigurableProduct([1000]);

    $product->categories()->attach($footwear->id);

    createCategoryConditionCatalogRule($this, '!{}', $footwear);

    expect(guestListedPrice($product))->toBe(1000.0)
        ->and(guestListedPrice($product->variants->first()))->toBe(1000.0);
});

it('should discount the variants of a configurable product in a category a contains condition includes', function () {
    $footwear = createRuleConditionCategory();

    $product = $this->createConfigurableProduct([1000]);

    $product->categories()->attach($footwear->id);

    createCategoryConditionCatalogRule($this, '{}', $footwear);

    expect(guestListedPrice($product))->toBe(800.0)
        ->and(guestListedPrice($product->variants->first()))->toBe(800.0);
});

it('should leave out a variant placed in an excluded category even when its configurable product is not in it', function () {
    $footwear = createRuleConditionCategory();

    $product = $this->createConfigurableProduct([1000]);

    $variant = $product->variants->first();

    $variant->categories()->attach($footwear->id);

    createCategoryConditionCatalogRule($this, '!{}', $footwear);

    expect(guestListedPrice($variant))->toBe(1000.0);
});

it('should leave out a variant listed in another category when its configurable product is in an excluded category', function () {
    $footwear = createRuleConditionCategory();

    $sale = createRuleConditionCategory();

    $product = $this->createConfigurableProduct([1000]);

    $product->categories()->attach($footwear->id);

    $variant = $product->variants->first();

    $variant->categories()->attach($sale->id);

    createCategoryConditionCatalogRule($this, '!{}', $footwear);

    expect(guestListedPrice($variant))->toBe(1000.0);
});

it('should discount a variant placed in an included category even when its configurable product is not in it', function () {
    $sale = createRuleConditionCategory();

    $product = $this->createConfigurableProduct([1000]);

    $variant = $product->variants->first();

    $variant->categories()->attach($sale->id);

    createCategoryConditionCatalogRule($this, '{}', $sale);

    expect(guestListedPrice($variant))->toBe(800.0);
});

it('should match a variant by a value only its configurable product carries', function () {
    $product = $this->createConfigurableProduct([1000]);

    $variant = $product->variants->first();

    $productNumber = Attribute::where('code', 'product_number')->firstOrFail();

    ProductAttributeValue::where('product_id', $variant->id)
        ->where('attribute_id', $productNumber->id)
        ->delete();

    ProductAttributeValue::create([
        'product_id' => $product->id,
        'attribute_id' => $productNumber->id,
        'text_value' => $number = 'PN-'.$product->id,
        'unique_id' => $product->id.'|'.$productNumber->id,
    ]);

    $this->createCatalogRuleForPricing([
        'action_type' => 'by_percent',
        'discount_amount' => 20,
        'condition_type' => 1,
        'conditions' => [[
            'attribute' => 'product|product_number',
            'operator' => '==',
            'value' => $number,
            'attribute_type' => 'text',
        ]],
    ], [1, 2, 3]);

    expect(guestListedPrice($variant))->toBe(800.0);
});

it('should reprice the variants when their configurable product moves into an excluded category', function () {
    $footwear = createRuleConditionCategory();

    $product = $this->createConfigurableProduct([1000]);

    createCategoryConditionCatalogRule($this, '!{}', $footwear);

    $product->categories()->attach($footwear->id);

    Event::dispatch('catalog.product.update.after', $product->fresh());

    expect(guestListedPrice($product))->toBe(1000.0);
});

it('should reprice a variant and its configurable product when the variant moves into an excluded category', function () {
    $footwear = createRuleConditionCategory();

    $product = $this->createConfigurableProduct([1000]);

    $variant = $product->variants->first();

    createCategoryConditionCatalogRule($this, '!{}', $footwear);

    $variant->categories()->attach($footwear->id);

    Event::dispatch('catalog.product.update.after', $variant->fresh());

    expect(guestListedPrice($variant))->toBe(1000.0)
        ->and(guestListedPrice($product))->toBe(1000.0);
});

it('should not apply a cart rule to a configurable product in a category a does not contain condition excludes', function () {
    $footwear = createRuleConditionCategory();

    $product = $this->createConfigurableProduct([500]);

    $product->categories()->attach($footwear->id);

    $this->createCartRuleForPricing([
        'action_type' => 'by_fixed',
        'discount_amount' => 50,
        'uses_attribute_conditions' => 1,
        'condition_type' => 1,
        'conditions' => [[
            'attribute' => 'product|category_ids',
            'operator' => '!{}',
            'value' => [(string) $footwear->id],
            'attribute_type' => 'multiselect',
        ]],
    ]);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 0);
});

it('should apply a cart rule to a configurable product in a category a contains condition includes', function () {
    $footwear = createRuleConditionCategory();

    $product = $this->createConfigurableProduct([500]);

    $product->categories()->attach($footwear->id);

    $this->createCartRuleForPricing([
        'action_type' => 'by_fixed',
        'discount_amount' => 50,
        'uses_attribute_conditions' => 1,
        'condition_type' => 1,
        'conditions' => [[
            'attribute' => 'product|category_ids',
            'operator' => '{}',
            'value' => [(string) $footwear->id],
            'attribute_type' => 'multiselect',
        ]],
    ]);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 50);
});
