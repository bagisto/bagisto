<?php

use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\Customer\Models\Customer;

/**
 * Create a cart rule for configurable pricing tests.
 */
function createConfigurableCartRule(array $overrides = [], array $customerGroups = [1, 2, 3]): CartRule
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
 * Add a configurable product's first variant to the cart.
 */
function addConfigurableToCart($test, $product, $qty = 1)
{
    $variant = $product->variants->first();

    return $test->addProductToCart($product->id, $qty, [
        'selected_configurable_option' => $variant->id,
    ]);
}

// ============================================================================
// No Coupon — Fixed Discount
// ============================================================================

it('should apply fixed cart rule discount to configurable product for all groups', function () {
    $product = $this->createConfigurableProduct([500]);

    createConfigurableCartRule(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = addConfigurableToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 50);
});

it('should apply fixed cart rule discount to configurable product for guest', function () {
    $product = $this->createConfigurableProduct([500]);

    createConfigurableCartRule(['action_type' => 'by_fixed', 'discount_amount' => 25], [1]);

    $response = addConfigurableToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 25);
});

it('should apply fixed cart rule discount to configurable product for general customer', function () {
    $product = $this->createConfigurableProduct([500]);

    createConfigurableCartRule(['action_type' => 'by_fixed', 'discount_amount' => 40], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = addConfigurableToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 40);
});

it('should apply fixed cart rule discount to configurable product for wholesaler', function () {
    $product = $this->createConfigurableProduct([500]);

    createConfigurableCartRule(['action_type' => 'by_fixed', 'discount_amount' => 60], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = addConfigurableToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 60);
});

// ============================================================================
// No Coupon — Percentage Discount
// ============================================================================

it('should apply percentage cart rule discount to configurable product for all groups', function () {
    $product = $this->createConfigurableProduct([1000]);

    createConfigurableCartRule(['action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = addConfigurableToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 100);
});

it('should apply percentage cart rule discount to configurable product for guest', function () {
    $product = $this->createConfigurableProduct([1000]);

    createConfigurableCartRule(['action_type' => 'by_percent', 'discount_amount' => 15], [1]);

    $response = addConfigurableToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 150);
});

it('should apply percentage cart rule discount to configurable product for general customer', function () {
    $product = $this->createConfigurableProduct([1000]);

    createConfigurableCartRule(['action_type' => 'by_percent', 'discount_amount' => 20], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = addConfigurableToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 200);
});

it('should apply percentage cart rule discount to configurable product for wholesaler', function () {
    $product = $this->createConfigurableProduct([1000]);

    createConfigurableCartRule(['action_type' => 'by_percent', 'discount_amount' => 25], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = addConfigurableToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 250);
});

// ============================================================================
// Specific Coupon
// ============================================================================

it('should apply coupon with fixed discount to configurable product for all groups', function () {
    $product = $this->createConfigurableProduct([500]);

    $cartRule = createConfigurableCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 75,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'CSAVE75',
    ]);

    addConfigurableToCart($this, $product)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 75);
    $response->assertJsonPath('data.coupon_code', $code);
});

it('should apply coupon with fixed discount to configurable product for guest', function () {
    $product = $this->createConfigurableProduct([500]);

    $cartRule = createConfigurableCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 50,
    ], [1]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'CGUEST50',
    ]);

    addConfigurableToCart($this, $product)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 50);
});

it('should apply coupon with fixed discount to configurable product for general customer', function () {
    $product = $this->createConfigurableProduct([500]);

    $cartRule = createConfigurableCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 60,
    ], [2]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'CGEN60',
    ]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    addConfigurableToCart($this, $product)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 60);
});

it('should apply coupon with fixed discount to configurable product for wholesaler', function () {
    $product = $this->createConfigurableProduct([500]);

    $cartRule = createConfigurableCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 80,
    ], [3]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'CWHOLE80',
    ]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    addConfigurableToCart($this, $product)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 80);
});

it('should apply coupon with percentage discount to configurable product for all groups', function () {
    $product = $this->createConfigurableProduct([1000]);

    $cartRule = createConfigurableCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_percent',
        'discount_amount' => 20,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'CSAVE20',
    ]);

    addConfigurableToCart($this, $product)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 200);
    $response->assertJsonPath('data.coupon_code', $code);
});
