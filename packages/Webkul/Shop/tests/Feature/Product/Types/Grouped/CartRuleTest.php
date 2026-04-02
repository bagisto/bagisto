<?php

use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\Customer\Models\Customer;

/**
 * Create a cart rule for grouped product pricing tests.
 */
function createGroupedCartRule(array $overrides = [], array $customerGroups = [1, 2, 3]): CartRule
{
    return CartRule::factory()->afterCreating(function (CartRule $rule) use ($customerGroups) {
        $rule->cart_rule_customer_groups()->sync($customerGroups);
        $rule->cart_rule_channels()->sync([1]);
    })->create(array_merge([
        'status' => 1,
        'action_type' => 'by_fixed',
        'discount_amount' => 50,
        'coupon_type' => 0,
    ], $overrides));
}

/**
 * Add a grouped product to the cart with qty 1 for each associated product.
 */
function addGroupedToCart($test, $product, int $quantity = 1)
{
    $product->load('grouped_products');

    $qty = [];

    foreach ($product->grouped_products as $gp) {
        $qty[$gp->associated_product_id] = $quantity;
    }

    return $test->addProductToCart($product->id, 1, ['qty' => $qty]);
}

// ============================================================================
// No Coupon — Fixed Discount
// ============================================================================

it('should apply fixed cart rule discount to grouped product for all groups', function () {
    $product = $this->createGroupedProduct([500, 500]);

    createGroupedCartRule(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = addGroupedToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 100);
});

it('should apply fixed cart rule discount to grouped product for guest', function () {
    $product = $this->createGroupedProduct([500, 500]);

    createGroupedCartRule(['action_type' => 'by_fixed', 'discount_amount' => 30], [1]);

    $response = addGroupedToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 60);
});

it('should apply fixed cart rule discount to grouped product for general customer', function () {
    $product = $this->createGroupedProduct([500, 500]);

    createGroupedCartRule(['action_type' => 'by_fixed', 'discount_amount' => 40], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = addGroupedToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 80);
});

it('should apply fixed cart rule discount to grouped product for wholesaler', function () {
    $product = $this->createGroupedProduct([500, 500]);

    createGroupedCartRule(['action_type' => 'by_fixed', 'discount_amount' => 60], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = addGroupedToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 120);
});

// ============================================================================
// No Coupon — Percentage Discount
// ============================================================================

it('should apply percentage cart rule discount to grouped product for all groups', function () {
    $product = $this->createGroupedProduct([500, 500]);

    createGroupedCartRule(['action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = addGroupedToCart($this, $product)->assertOk();

    // 10% of (500 + 500) = 100
    $this->assertCartDiscount($response, 100);
});

it('should apply percentage cart rule discount to grouped product for guest', function () {
    $product = $this->createGroupedProduct([500, 500]);

    createGroupedCartRule(['action_type' => 'by_percent', 'discount_amount' => 15], [1]);

    $response = addGroupedToCart($this, $product)->assertOk();

    // 15% of (500 + 500) = 150
    $this->assertCartDiscount($response, 150);
});

it('should apply percentage cart rule discount to grouped product for general customer', function () {
    $product = $this->createGroupedProduct([500, 500]);

    createGroupedCartRule(['action_type' => 'by_percent', 'discount_amount' => 20], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = addGroupedToCart($this, $product)->assertOk();

    // 20% of (500 + 500) = 200
    $this->assertCartDiscount($response, 200);
});

it('should apply percentage cart rule discount to grouped product for wholesaler', function () {
    $product = $this->createGroupedProduct([500, 500]);

    createGroupedCartRule(['action_type' => 'by_percent', 'discount_amount' => 25], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = addGroupedToCart($this, $product)->assertOk();

    // 25% of (500 + 500) = 250
    $this->assertCartDiscount($response, 250);
});

// ============================================================================
// Specific Coupon
// ============================================================================

it('should apply coupon with fixed discount to grouped product for all groups', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $cartRule = createGroupedCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 75,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'GSAVE75',
    ]);

    addGroupedToCart($this, $product)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 150);
    $response->assertJsonPath('data.coupon_code', $code);
});

it('should apply coupon with fixed discount to grouped product for guest', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $cartRule = createGroupedCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 50,
    ], [1]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'GGUEST50',
    ]);

    addGroupedToCart($this, $product)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 100);
});

it('should apply coupon with fixed discount to grouped product for general customer', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $cartRule = createGroupedCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 60,
    ], [2]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'GGEN60',
    ]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    addGroupedToCart($this, $product)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 120);
});

it('should apply coupon with fixed discount to grouped product for wholesaler', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $cartRule = createGroupedCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 80,
    ], [3]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'GWHOLE80',
    ]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    addGroupedToCart($this, $product)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 160);
});

it('should apply coupon with percentage discount to grouped product for all groups', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $cartRule = createGroupedCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_percent',
        'discount_amount' => 20,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'GSAVE20',
    ]);

    addGroupedToCart($this, $product)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    // 20% of (500 + 500) = 200
    $this->assertCartDiscount($response, 200);
    $response->assertJsonPath('data.coupon_code', $code);
});
