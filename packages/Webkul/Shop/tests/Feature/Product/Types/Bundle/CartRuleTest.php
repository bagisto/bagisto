<?php

use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\Customer\Models\Customer;

/**
 * Create a cart rule for bundle pricing tests.
 */
function createBundleCartRule(array $overrides = [], array $customerGroups = [1, 2, 3]): CartRule
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
 * Add a bundle product to the cart by selecting the first option product.
 */
function addBundleToCart($test, $product, $qty = 1)
{
    $product->load('bundle_options.bundle_option_products');
    $option = $product->bundle_options->first();
    $firstOptionProduct = $option->bundle_option_products->first();

    return $test->addProductToCart($product->id, $qty, [
        'bundle_options' => [$option->id => [$firstOptionProduct->id]],
        'bundle_option_qty' => [$option->id => 1],
    ]);
}

// ============================================================================
// No Coupon — Fixed Discount
// ============================================================================

it('should apply fixed cart rule discount to bundle product for all groups', function () {
    $product = $this->createBundleProduct([500]);

    createBundleCartRule(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = addBundleToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 50);
});

it('should apply fixed cart rule discount to bundle product for guest', function () {
    $product = $this->createBundleProduct([500]);

    createBundleCartRule(['action_type' => 'by_fixed', 'discount_amount' => 25], [1]);

    $response = addBundleToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 25);
});

it('should apply fixed cart rule discount to bundle product for general customer', function () {
    $product = $this->createBundleProduct([500]);

    createBundleCartRule(['action_type' => 'by_fixed', 'discount_amount' => 40], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = addBundleToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 40);
});

it('should apply fixed cart rule discount to bundle product for wholesaler', function () {
    $product = $this->createBundleProduct([500]);

    createBundleCartRule(['action_type' => 'by_fixed', 'discount_amount' => 60], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = addBundleToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 60);
});

// ============================================================================
// No Coupon — Percentage Discount
// ============================================================================

it('should apply percentage cart rule discount to bundle product for all groups', function () {
    $product = $this->createBundleProduct([1000]);

    createBundleCartRule(['action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = addBundleToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 100);
});

it('should apply percentage cart rule discount to bundle product for guest', function () {
    $product = $this->createBundleProduct([1000]);

    createBundleCartRule(['action_type' => 'by_percent', 'discount_amount' => 15], [1]);

    $response = addBundleToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 150);
});

it('should apply percentage cart rule discount to bundle product for general customer', function () {
    $product = $this->createBundleProduct([1000]);

    createBundleCartRule(['action_type' => 'by_percent', 'discount_amount' => 20], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = addBundleToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 200);
});

it('should apply percentage cart rule discount to bundle product for wholesaler', function () {
    $product = $this->createBundleProduct([1000]);

    createBundleCartRule(['action_type' => 'by_percent', 'discount_amount' => 25], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = addBundleToCart($this, $product)->assertOk();

    $this->assertCartDiscount($response, 250);
});

// ============================================================================
// Specific Coupon
// ============================================================================

it('should apply coupon with fixed discount to bundle product for all groups', function () {
    $product = $this->createBundleProduct([500]);

    $cartRule = createBundleCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 75,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'BSAVE75',
    ]);

    addBundleToCart($this, $product)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 75);
    $response->assertJsonPath('data.coupon_code', $code);
});

it('should apply coupon with fixed discount to bundle product for guest', function () {
    $product = $this->createBundleProduct([500]);

    $cartRule = createBundleCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 50,
    ], [1]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'BGUEST50',
    ]);

    addBundleToCart($this, $product)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 50);
});

it('should apply coupon with fixed discount to bundle product for general customer', function () {
    $product = $this->createBundleProduct([500]);

    $cartRule = createBundleCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 60,
    ], [2]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'BGEN60',
    ]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    addBundleToCart($this, $product)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 60);
});

it('should apply coupon with fixed discount to bundle product for wholesaler', function () {
    $product = $this->createBundleProduct([500]);

    $cartRule = createBundleCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 80,
    ], [3]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'BWHOLE80',
    ]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    addBundleToCart($this, $product)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 80);
});

it('should apply coupon with percentage discount to bundle product for all groups', function () {
    $product = $this->createBundleProduct([1000]);

    $cartRule = createBundleCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_percent',
        'discount_amount' => 20,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'BSAVE20',
    ]);

    addBundleToCart($this, $product)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 200);
    $response->assertJsonPath('data.coupon_code', $code);
});
