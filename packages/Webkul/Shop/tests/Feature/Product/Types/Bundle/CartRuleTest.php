<?php

// ============================================================================
// Automatic Discounts
// ============================================================================

it('should apply a fixed cart rule discount to a bundle product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createBundleProduct([500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 50);
})->with('customer groups');

it('should apply a percentage cart rule discount to a bundle product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createBundleProduct([1000]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 15], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 150);
})->with('customer groups');

it('should not apply a cart rule limited to another customer group to a bundle product', function () {
    $product = $this->createBundleProduct([500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50], [3]);

    $this->actAsCustomerGroup(2);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 0);
});

// ============================================================================
// Coupons
// ============================================================================

it('should apply a fixed coupon discount to a bundle product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createBundleProduct([500]);

    $this->createCouponCartRule('BSAVE75', ['action_type' => 'by_fixed', 'discount_amount' => 75], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $this->addBundleProductToCart($product)->assertOk();

    $response = $this->applyCoupon('BSAVE75')
        ->assertOk()
        ->assertJsonPath('data.coupon_code', 'BSAVE75');

    $this->assertCartDiscount($response, 75);
})->with('customer groups');

it('should apply a percentage coupon discount to a bundle product', function () {
    $product = $this->createBundleProduct([1000]);

    $this->createCouponCartRule('BSAVE20', ['action_type' => 'by_percent', 'discount_amount' => 20]);

    $this->addBundleProductToCart($product)->assertOk();

    $response = $this->applyCoupon('BSAVE20')
        ->assertOk()
        ->assertJsonPath('data.coupon_code', 'BSAVE20');

    $this->assertCartDiscount($response, 200);
});
