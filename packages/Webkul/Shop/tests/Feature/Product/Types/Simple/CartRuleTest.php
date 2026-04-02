<?php

use Webkul\CartRule\Models\CartRule;
use Webkul\CartRule\Models\CartRuleCoupon;
use Webkul\Customer\Models\Customer;

/**
 * Create a cart rule for pricing tests.
 */
function createPricingCartRule(array $overrides = [], array $customerGroups = [1, 2, 3]): CartRule
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

// ============================================================================
// No Coupon — Fixed Discount
// ============================================================================

it('should apply fixed cart rule discount for all customer groups', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    createPricingCartRule(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartDiscount($response, 50);
});

it('should apply fixed cart rule discount for guest customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    createPricingCartRule(['action_type' => 'by_fixed', 'discount_amount' => 30], [1]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartDiscount($response, 30);
});

it('should apply fixed cart rule discount for general customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    createPricingCartRule(['action_type' => 'by_fixed', 'discount_amount' => 40], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartDiscount($response, 40);
});

it('should apply fixed cart rule discount for wholesaler customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    createPricingCartRule(['action_type' => 'by_fixed', 'discount_amount' => 60], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartDiscount($response, 60);
});

// ============================================================================
// No Coupon — Percentage Discount
// ============================================================================

it('should apply percentage cart rule discount for all customer groups', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    createPricingCartRule(['action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartDiscount($response, 100);
});

it('should apply percentage cart rule discount for guest customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    createPricingCartRule(['action_type' => 'by_percent', 'discount_amount' => 15], [1]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartDiscount($response, 150);
});

it('should apply percentage cart rule discount for general customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    createPricingCartRule(['action_type' => 'by_percent', 'discount_amount' => 20], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartDiscount($response, 200);
});

it('should apply percentage cart rule discount for wholesaler customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    createPricingCartRule(['action_type' => 'by_percent', 'discount_amount' => 25], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartDiscount($response, 250);
});

// ============================================================================
// Specific Coupon
// ============================================================================

it('should apply coupon with fixed discount for all customer groups', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    $cartRule = createPricingCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 75,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'SAVE75',
    ]);

    $this->addProductToCart($product->id)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 75);
    $response->assertJsonPath('data.coupon_code', $code);
});

it('should apply coupon with fixed discount for guest customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    $cartRule = createPricingCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 50,
    ], [1]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'GUEST50',
    ]);

    $this->addProductToCart($product->id)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 50);
});

it('should apply coupon with fixed discount for general customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    $cartRule = createPricingCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 60,
    ], [2]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'GEN60',
    ]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $this->addProductToCart($product->id)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 60);
});

it('should apply coupon with fixed discount for wholesaler customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    $cartRule = createPricingCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_fixed',
        'discount_amount' => 80,
    ], [3]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'WHOLE80',
    ]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $this->addProductToCart($product->id)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 80);
});

it('should apply coupon with percentage discount for all customer groups', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $cartRule = createPricingCartRule([
        'coupon_type' => 1,
        'use_auto_generation' => 0,
        'action_type' => 'by_percent',
        'discount_amount' => 20,
    ]);

    CartRuleCoupon::factory()->create([
        'cart_rule_id' => $cartRule->id,
        'code' => $code = 'SAVE20',
    ]);

    $this->addProductToCart($product->id)->assertOk();

    $response = $this->applyCoupon($code)->assertOk();

    $this->assertCartDiscount($response, 200);
    $response->assertJsonPath('data.coupon_code', $code);
});
