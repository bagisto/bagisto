<?php

use Webkul\Customer\Models\Customer;

// ============================================================================
// Percentage Catalog Rule
// ============================================================================

it('should apply percentage catalog rule to virtual product for guest', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 400);
});

it('should apply percentage catalog rule to virtual product for general customer', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 25], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 375);
});

it('should apply percentage catalog rule to virtual product for wholesaler', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 30], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 350);
});

// ============================================================================
// Fixed Catalog Rule
// ============================================================================

it('should apply fixed catalog rule to virtual product for guest', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 75], [1, 2, 3]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 425);
});

it('should apply fixed catalog rule to virtual product for general customer', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 100], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 400);
});

it('should apply fixed catalog rule to virtual product for wholesaler', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 150], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 350);
});
