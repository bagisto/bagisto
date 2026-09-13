<?php

// ============================================================================
// Automatic Discounts
// ============================================================================

it('should apply a fixed cart rule discount to a simple product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartDiscount($response, 50);
})->with('customer groups');

it('should apply a percentage cart rule discount to a simple product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 15], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartDiscount($response, 150);
})->with('customer groups');

it('should not apply a cart rule limited to another customer group to a simple product', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50], [3]);

    $this->actAsCustomerGroup(2);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartDiscount($response, 0);
});

// ============================================================================
// Coupons
// ============================================================================

it('should apply a fixed coupon discount to a simple product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 500]]);

    $this->createCouponCartRule('SAVE75', ['action_type' => 'by_fixed', 'discount_amount' => 75], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $this->addProductToCart($product->id)->assertOk();

    $response = $this->applyCoupon('SAVE75')
        ->assertOk()
        ->assertJsonPath('data.coupon_code', 'SAVE75');

    $this->assertCartDiscount($response, 75);
})->with('customer groups');

it('should apply a percentage coupon discount to a simple product', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->createCouponCartRule('SAVE20', ['action_type' => 'by_percent', 'discount_amount' => 20]);

    $this->addProductToCart($product->id)->assertOk();

    $response = $this->applyCoupon('SAVE20')
        ->assertOk()
        ->assertJsonPath('data.coupon_code', 'SAVE20');

    $this->assertCartDiscount($response, 200);
});
