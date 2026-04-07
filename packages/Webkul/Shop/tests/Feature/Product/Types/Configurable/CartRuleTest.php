<?php

use Webkul\Customer\Models\Customer;

// ============================================================================
// No Coupon — Fixed Discount
// ============================================================================

it('should apply fixed cart rule discount to configurable product for all groups', function () {
    $product = $this->createConfigurableProduct([500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 50);
});

it('should apply fixed cart rule discount to configurable product for guest', function () {
    $product = $this->createConfigurableProduct([500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 25], [1]);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 25);
});

it('should apply fixed cart rule discount to configurable product for general customer', function () {
    $product = $this->createConfigurableProduct([500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 40], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 40);
});

it('should apply fixed cart rule discount to configurable product for wholesaler', function () {
    $product = $this->createConfigurableProduct([500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 60], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 60);
});

// ============================================================================
// No Coupon — Percentage Discount
// ============================================================================

it('should apply percentage cart rule discount to configurable product for all groups', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 100);
});

it('should apply percentage cart rule discount to configurable product for guest', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 15], [1]);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 150);
});

it('should apply percentage cart rule discount to configurable product for general customer', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 200);
});

it('should apply percentage cart rule discount to configurable product for wholesaler', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 25], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 250);
});

// ============================================================================
// Specific Coupon
// ============================================================================

it('should apply coupon with fixed discount to configurable product for all groups', function () {
    $product = $this->createConfigurableProduct([500]);

    $this->createCouponCartRule('CSAVE75', ['action_type' => 'by_fixed', 'discount_amount' => 75]);

    $this->addConfigurableProductToCart($product)->assertOk();

    $response = $this->applyCoupon('CSAVE75')->assertOk();

    $this->assertCartDiscount($response, 75);
    $response->assertJsonPath('data.coupon_code', 'CSAVE75');
});

it('should apply coupon with fixed discount to configurable product for guest', function () {
    $product = $this->createConfigurableProduct([500]);

    $this->createCouponCartRule('CGUEST50', ['action_type' => 'by_fixed', 'discount_amount' => 50], [1]);

    $this->addConfigurableProductToCart($product)->assertOk();

    $response = $this->applyCoupon('CGUEST50')->assertOk();

    $this->assertCartDiscount($response, 50);
});

it('should apply coupon with fixed discount to configurable product for general customer', function () {
    $product = $this->createConfigurableProduct([500]);

    $this->createCouponCartRule('CGEN60', ['action_type' => 'by_fixed', 'discount_amount' => 60], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $this->addConfigurableProductToCart($product)->assertOk();

    $response = $this->applyCoupon('CGEN60')->assertOk();

    $this->assertCartDiscount($response, 60);
});

it('should apply coupon with fixed discount to configurable product for wholesaler', function () {
    $product = $this->createConfigurableProduct([500]);

    $this->createCouponCartRule('CWHOLE80', ['action_type' => 'by_fixed', 'discount_amount' => 80], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $this->addConfigurableProductToCart($product)->assertOk();

    $response = $this->applyCoupon('CWHOLE80')->assertOk();

    $this->assertCartDiscount($response, 80);
});

it('should apply coupon with percentage discount to configurable product for all groups', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCouponCartRule('CSAVE20', ['action_type' => 'by_percent', 'discount_amount' => 20]);

    $this->addConfigurableProductToCart($product)->assertOk();

    $response = $this->applyCoupon('CSAVE20')->assertOk();

    $this->assertCartDiscount($response, 200);
    $response->assertJsonPath('data.coupon_code', 'CSAVE20');
});
