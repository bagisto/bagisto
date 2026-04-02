<?php

use Webkul\Customer\Models\Customer;

// ============================================================================
// No Coupon — Fixed Discount
// ============================================================================

it('should apply fixed cart rule discount to grouped product for all groups', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 100);
});

it('should apply fixed cart rule discount to grouped product for guest', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 30], [1]);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 60);
});

it('should apply fixed cart rule discount to grouped product for general customer', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 40], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 80);
});

it('should apply fixed cart rule discount to grouped product for wholesaler', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 60], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 120);
});

// ============================================================================
// No Coupon — Percentage Discount
// ============================================================================

it('should apply percentage cart rule discount to grouped product for all groups', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    // 10% of (500 + 500) = 100
    $this->assertCartDiscount($response, 100);
});

it('should apply percentage cart rule discount to grouped product for guest', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 15], [1]);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    // 15% of (500 + 500) = 150
    $this->assertCartDiscount($response, 150);
});

it('should apply percentage cart rule discount to grouped product for general customer', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    // 20% of (500 + 500) = 200
    $this->assertCartDiscount($response, 200);
});

it('should apply percentage cart rule discount to grouped product for wholesaler', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 25], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    // 25% of (500 + 500) = 250
    $this->assertCartDiscount($response, 250);
});

// ============================================================================
// Specific Coupon
// ============================================================================

it('should apply coupon with fixed discount to grouped product for all groups', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCouponCartRule('GSAVE75', ['action_type' => 'by_fixed', 'discount_amount' => 75]);

    $this->addGroupedProductToCart($product)->assertOk();

    $response = $this->applyCoupon('GSAVE75')->assertOk();

    $this->assertCartDiscount($response, 150);
    $response->assertJsonPath('data.coupon_code', 'GSAVE75');
});

it('should apply coupon with fixed discount to grouped product for guest', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCouponCartRule('GGUEST50', ['action_type' => 'by_fixed', 'discount_amount' => 50], [1]);

    $this->addGroupedProductToCart($product)->assertOk();

    $response = $this->applyCoupon('GGUEST50')->assertOk();

    $this->assertCartDiscount($response, 100);
});

it('should apply coupon with fixed discount to grouped product for general customer', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCouponCartRule('GGEN60', ['action_type' => 'by_fixed', 'discount_amount' => 60], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $this->addGroupedProductToCart($product)->assertOk();

    $response = $this->applyCoupon('GGEN60')->assertOk();

    $this->assertCartDiscount($response, 120);
});

it('should apply coupon with fixed discount to grouped product for wholesaler', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCouponCartRule('GWHOLE80', ['action_type' => 'by_fixed', 'discount_amount' => 80], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $this->addGroupedProductToCart($product)->assertOk();

    $response = $this->applyCoupon('GWHOLE80')->assertOk();

    $this->assertCartDiscount($response, 160);
});

it('should apply coupon with percentage discount to grouped product for all groups', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCouponCartRule('GSAVE20', ['action_type' => 'by_percent', 'discount_amount' => 20]);

    $this->addGroupedProductToCart($product)->assertOk();

    $response = $this->applyCoupon('GSAVE20')->assertOk();

    // 20% of (500 + 500) = 200
    $this->assertCartDiscount($response, 200);
    $response->assertJsonPath('data.coupon_code', 'GSAVE20');
});
