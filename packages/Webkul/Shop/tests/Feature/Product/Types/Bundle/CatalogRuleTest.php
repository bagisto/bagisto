<?php

use Webkul\Customer\Models\Customer;

// ============================================================================
// Percentage Catalog Rule
// ============================================================================

it('should apply percentage catalog rule to bundle product for guest', function () {
    $product = $this->createBundleProduct([1000]);

    $product->load('bundle_options.bundle_option_products');
    $option = $product->bundle_options->first();
    $firstOptionProduct = $option->bundle_option_products->first();

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    $response = $this->addProductToCart($product->id, 1, [
        'bundle_options' => [$option->id => [$firstOptionProduct->id]],
        'bundle_option_qty' => [$option->id => 1],
    ])->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should apply percentage catalog rule to bundle product for general customer', function () {
    $product = $this->createBundleProduct([1000]);

    $product->load('bundle_options.bundle_option_products');
    $option = $product->bundle_options->first();
    $firstOptionProduct = $option->bundle_option_products->first();

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 25], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, [
        'bundle_options' => [$option->id => [$firstOptionProduct->id]],
        'bundle_option_qty' => [$option->id => 1],
    ])->assertOk();

    $this->assertCartItemPrice($response, 750);
});

it('should apply percentage catalog rule to bundle product for wholesaler', function () {
    $product = $this->createBundleProduct([1000]);

    $product->load('bundle_options.bundle_option_products');
    $option = $product->bundle_options->first();
    $firstOptionProduct = $option->bundle_option_products->first();

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 30], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, [
        'bundle_options' => [$option->id => [$firstOptionProduct->id]],
        'bundle_option_qty' => [$option->id => 1],
    ])->assertOk();

    $this->assertCartItemPrice($response, 700);
});

// ============================================================================
// Fixed Catalog Rule
// ============================================================================

it('should apply fixed catalog rule to bundle product for guest', function () {
    $product = $this->createBundleProduct([1000]);

    $product->load('bundle_options.bundle_option_products');
    $option = $product->bundle_options->first();
    $firstOptionProduct = $option->bundle_option_products->first();

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 150], [1, 2, 3]);

    $response = $this->addProductToCart($product->id, 1, [
        'bundle_options' => [$option->id => [$firstOptionProduct->id]],
        'bundle_option_qty' => [$option->id => 1],
    ])->assertOk();

    $this->assertCartItemPrice($response, 850);
});

it('should apply fixed catalog rule to bundle product for general customer', function () {
    $product = $this->createBundleProduct([1000]);

    $product->load('bundle_options.bundle_option_products');
    $option = $product->bundle_options->first();
    $firstOptionProduct = $option->bundle_option_products->first();

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 200], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, [
        'bundle_options' => [$option->id => [$firstOptionProduct->id]],
        'bundle_option_qty' => [$option->id => 1],
    ])->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should apply fixed catalog rule to bundle product for wholesaler', function () {
    $product = $this->createBundleProduct([1000]);

    $product->load('bundle_options.bundle_option_products');
    $option = $product->bundle_options->first();
    $firstOptionProduct = $option->bundle_option_products->first();

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 250], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, [
        'bundle_options' => [$option->id => [$firstOptionProduct->id]],
        'bundle_option_qty' => [$option->id => 1],
    ])->assertOk();

    $this->assertCartItemPrice($response, 750);
});
