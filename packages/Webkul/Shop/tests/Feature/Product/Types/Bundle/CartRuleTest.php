<?php

use Webkul\Customer\Models\Customer;

// ============================================================================
// No Coupon — Fixed Discount
// ============================================================================

it('should apply fixed cart rule discount to bundle product for all groups', function () {
    $product = $this->createBundleProduct([500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 50);
});

it('should apply fixed cart rule discount to bundle product for guest', function () {
    $product = $this->createBundleProduct([500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 25], [1]);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 25);
});

it('should apply fixed cart rule discount to bundle product for general customer', function () {
    $product = $this->createBundleProduct([500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 40], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 40);
});

it('should apply fixed cart rule discount to bundle product for wholesaler', function () {
    $product = $this->createBundleProduct([500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 60], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 60);
});

// ============================================================================
// No Coupon — Percentage Discount
// ============================================================================

it('should apply percentage cart rule discount to bundle product for all groups', function () {
    $product = $this->createBundleProduct([1000]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 100);
});

it('should apply percentage cart rule discount to bundle product for guest', function () {
    $product = $this->createBundleProduct([1000]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 15], [1]);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 150);
});

it('should apply percentage cart rule discount to bundle product for general customer', function () {
    $product = $this->createBundleProduct([1000]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 200);
});

it('should apply percentage cart rule discount to bundle product for wholesaler', function () {
    $product = $this->createBundleProduct([1000]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 25], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 250);
});

// ============================================================================
// Specific Coupon
// ============================================================================

it('should apply coupon with fixed discount to bundle product for all groups', function () {
    $product = $this->createBundleProduct([500]);

    $this->createCouponCartRule('BSAVE75', ['action_type' => 'by_fixed', 'discount_amount' => 75]);

    $this->addBundleProductToCart($product)->assertOk();

    $response = $this->applyCoupon('BSAVE75')->assertOk();

    $this->assertCartDiscount($response, 75);
    $response->assertJsonPath('data.coupon_code', 'BSAVE75');
});

it('should apply coupon with fixed discount to bundle product for guest', function () {
    $product = $this->createBundleProduct([500]);

    $this->createCouponCartRule('BGUEST50', ['action_type' => 'by_fixed', 'discount_amount' => 50], [1]);

    $this->addBundleProductToCart($product)->assertOk();

    $response = $this->applyCoupon('BGUEST50')->assertOk();

    $this->assertCartDiscount($response, 50);
});

it('should apply coupon with fixed discount to bundle product for general customer', function () {
    $product = $this->createBundleProduct([500]);

    $this->createCouponCartRule('BGEN60', ['action_type' => 'by_fixed', 'discount_amount' => 60], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $this->addBundleProductToCart($product)->assertOk();

    $response = $this->applyCoupon('BGEN60')->assertOk();

    $this->assertCartDiscount($response, 60);
});

it('should apply coupon with fixed discount to bundle product for wholesaler', function () {
    $product = $this->createBundleProduct([500]);

    $this->createCouponCartRule('BWHOLE80', ['action_type' => 'by_fixed', 'discount_amount' => 80], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $this->addBundleProductToCart($product)->assertOk();

    $response = $this->applyCoupon('BWHOLE80')->assertOk();

    $this->assertCartDiscount($response, 80);
});

it('should apply coupon with percentage discount to bundle product for all groups', function () {
    $product = $this->createBundleProduct([1000]);

    $this->createCouponCartRule('BSAVE20', ['action_type' => 'by_percent', 'discount_amount' => 20]);

    $this->addBundleProductToCart($product)->assertOk();

    $response = $this->applyCoupon('BSAVE20')->assertOk();

    $this->assertCartDiscount($response, 200);
    $response->assertJsonPath('data.coupon_code', 'BSAVE20');
});
