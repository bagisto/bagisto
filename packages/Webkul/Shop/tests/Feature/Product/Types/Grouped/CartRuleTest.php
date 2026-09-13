<?php

// ============================================================================
// Automatic Discounts
// ============================================================================

it('should apply a fixed cart rule discount to every line of a grouped product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 100);
})->with('customer groups');

it('should apply a percentage cart rule discount to a grouped product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 15], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 150);
})->with('customer groups');

it('should not apply a cart rule limited to another customer group to a grouped product', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50], [3]);

    $this->actAsCustomerGroup(2);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 0);
});

// ============================================================================
// Coupons
// ============================================================================

it('should apply a fixed coupon discount to every line of a grouped product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCouponCartRule('GSAVE75', ['action_type' => 'by_fixed', 'discount_amount' => 75], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $this->addGroupedProductToCart($product)->assertOk();

    $response = $this->applyCoupon('GSAVE75')
        ->assertOk()
        ->assertJsonPath('data.coupon_code', 'GSAVE75');

    $this->assertCartDiscount($response, 150);
})->with('customer groups');

it('should apply a percentage coupon discount to a grouped product', function () {
    $product = $this->createGroupedProduct([500, 500]);

    $this->createCouponCartRule('GSAVE20', ['action_type' => 'by_percent', 'discount_amount' => 20]);

    $this->addGroupedProductToCart($product)->assertOk();

    $response = $this->applyCoupon('GSAVE20')
        ->assertOk()
        ->assertJsonPath('data.coupon_code', 'GSAVE20');

    $this->assertCartDiscount($response, 200);
});
