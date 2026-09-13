<?php

// ============================================================================
// Automatic Discounts
// ============================================================================

it('should apply a fixed cart rule discount to a downloadable product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 500]], [0]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 50);
})->with('customer groups');

it('should apply a percentage cart rule discount to a downloadable product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 15], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 150);
})->with('customer groups');

it('should not apply a cart rule limited to another customer group to a downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 500]], [0]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50], [3]);

    $this->actAsCustomerGroup(2);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 0);
});

// ============================================================================
// Coupons
// ============================================================================

it('should apply a fixed coupon discount to a downloadable product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 500]], [0]);

    $this->createCouponCartRule('DSAVE75', ['action_type' => 'by_fixed', 'discount_amount' => 75], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $this->addDownloadableProductToCart($product)->assertOk();

    $response = $this->applyCoupon('DSAVE75')
        ->assertOk()
        ->assertJsonPath('data.coupon_code', 'DSAVE75');

    $this->assertCartDiscount($response, 75);
})->with('customer groups');

it('should apply a percentage coupon discount to a downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCouponCartRule('DSAVE20', ['action_type' => 'by_percent', 'discount_amount' => 20]);

    $this->addDownloadableProductToCart($product)->assertOk();

    $response = $this->applyCoupon('DSAVE20')
        ->assertOk()
        ->assertJsonPath('data.coupon_code', 'DSAVE20');

    $this->assertCartDiscount($response, 200);
});
