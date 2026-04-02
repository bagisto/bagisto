<?php

use Webkul\Customer\Models\Customer;

// ============================================================================
// Percentage Catalog Rule
// ============================================================================

it('should apply percentage catalog rule discount for guest on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should apply percentage catalog rule discount for general customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 25], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 750);
});

it('should apply percentage catalog rule discount for wholesaler on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 30], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 700);
});

// ============================================================================
// Fixed Catalog Rule
// ============================================================================

it('should apply fixed catalog rule discount for guest on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 150], [1, 2, 3]);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 850);
});

it('should apply fixed catalog rule discount for general customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 200], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should apply fixed catalog rule discount for wholesaler on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 250], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addDownloadableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 750);
});
