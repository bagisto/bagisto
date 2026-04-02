<?php

use Webkul\Customer\Models\Customer;

// ============================================================================
// Percentage Catalog Rule
// ============================================================================

it('should apply percentage catalog rule to grouped product for guest', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    // 1000 - (1000 * 20 / 100) = 800
    $this->assertCartItemPrice($response, 800, 0);
});

it('should apply percentage catalog rule to grouped product for general customer', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 25], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    // 1000 - (1000 * 25 / 100) = 750
    $this->assertCartItemPrice($response, 750, 0);
});

it('should apply percentage catalog rule to grouped product for wholesaler', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 30], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    // 1000 - (1000 * 30 / 100) = 700
    $this->assertCartItemPrice($response, 700, 0);
});

// ============================================================================
// Fixed Catalog Rule
// ============================================================================

it('should apply fixed catalog rule to grouped product for guest', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 150], [1, 2, 3]);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    // 1000 - 150 = 850
    $this->assertCartItemPrice($response, 850, 0);
});

it('should apply fixed catalog rule to grouped product for general customer', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 200], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    // 1000 - 200 = 800
    $this->assertCartItemPrice($response, 800, 0);
});

it('should apply fixed catalog rule to grouped product for wholesaler', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 250], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    // 1000 - 250 = 750
    $this->assertCartItemPrice($response, 750, 0);
});
