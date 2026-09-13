<?php

// ============================================================================
// Automatic Discounts
// ============================================================================

it('should apply a fixed cart rule discount to a virtual product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartDiscount($response, 50);
})->with('customer groups');

it('should apply a percentage cart rule discount to a virtual product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 1000]]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 15], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartDiscount($response, 150);
})->with('customer groups');

it('should not apply a cart rule limited to another customer group to a virtual product', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50], [3]);

    $this->actAsCustomerGroup(2);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartDiscount($response, 0);
});

// ============================================================================
// Coupons
// ============================================================================

it('should apply a fixed coupon discount to a virtual product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    $this->createCouponCartRule('VSAVE75', ['action_type' => 'by_fixed', 'discount_amount' => 75], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $this->addProductToCart($product->id)->assertOk();

    $response = $this->applyCoupon('VSAVE75')
        ->assertOk()
        ->assertJsonPath('data.coupon_code', 'VSAVE75');

    $this->assertCartDiscount($response, 75);
})->with('customer groups');

it('should apply a percentage coupon discount to a virtual product', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 1000]]);

    $this->createCouponCartRule('VSAVE20', ['action_type' => 'by_percent', 'discount_amount' => 20]);

    $this->addProductToCart($product->id)->assertOk();

    $response = $this->applyCoupon('VSAVE20')
        ->assertOk()
        ->assertJsonPath('data.coupon_code', 'VSAVE20');

    $this->assertCartDiscount($response, 200);
});
