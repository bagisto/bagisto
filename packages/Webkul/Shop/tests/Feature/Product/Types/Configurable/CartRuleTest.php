<?php

use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;

// ============================================================================
// Automatic Discounts
// ============================================================================

it('should apply a fixed cart rule discount to a configurable product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createConfigurableProduct([500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 50);
})->with('customer groups');

it('should apply a percentage cart rule discount to a configurable product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCartRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 15], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 150);
})->with('customer groups');

it('should not apply a cart rule limited to another customer group to a configurable product', function () {
    $product = $this->createConfigurableProduct([500]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50], [3]);

    $this->actAsCustomerGroup(2);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 0);
});

// ============================================================================
// Coupons
// ============================================================================

it('should apply a fixed coupon discount to a configurable product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createConfigurableProduct([500]);

    $this->createCouponCartRule('CSAVE75', ['action_type' => 'by_fixed', 'discount_amount' => 75], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $this->addConfigurableProductToCart($product)->assertOk();

    $response = $this->applyCoupon('CSAVE75')
        ->assertOk()
        ->assertJsonPath('data.coupon_code', 'CSAVE75');

    $this->assertCartDiscount($response, 75);
})->with('customer groups');

it('should apply a percentage coupon discount to a configurable product', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCouponCartRule('CSAVE20', ['action_type' => 'by_percent', 'discount_amount' => 20]);

    $this->addConfigurableProductToCart($product)->assertOk();

    $response = $this->applyCoupon('CSAVE20')
        ->assertOk()
        ->assertJsonPath('data.coupon_code', 'CSAVE20');

    $this->assertCartDiscount($response, 200);
});

// ============================================================================
// Category Conditions
// ============================================================================

it('should apply a cart rule to a configurable product in a category a contains condition includes', function () {
    $footwear = Category::factory()->has(CategoryTranslation::factory(), 'translations')->create();

    $product = $this->createConfigurableProduct([500]);

    $product->categories()->attach($footwear->id);

    $this->createCartRuleForPricing([
        'action_type' => 'by_fixed',
        'discount_amount' => 50,
        'uses_attribute_conditions' => 1,
        'condition_type' => 1,
        'conditions' => [[
            'attribute' => 'product|category_ids',
            'operator' => '{}',
            'value' => [(string) $footwear->id],
            'attribute_type' => 'multiselect',
        ]],
    ]);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 50);
});

it('should not apply a cart rule to a configurable product in a category a does not contain condition excludes', function () {
    $footwear = Category::factory()->has(CategoryTranslation::factory(), 'translations')->create();

    $product = $this->createConfigurableProduct([500]);

    $product->categories()->attach($footwear->id);

    $this->createCartRuleForPricing([
        'action_type' => 'by_fixed',
        'discount_amount' => 50,
        'uses_attribute_conditions' => 1,
        'condition_type' => 1,
        'conditions' => [[
            'attribute' => 'product|category_ids',
            'operator' => '!{}',
            'value' => [(string) $footwear->id],
            'attribute_type' => 'multiselect',
        ]],
    ]);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartDiscount($response, 0);
});
