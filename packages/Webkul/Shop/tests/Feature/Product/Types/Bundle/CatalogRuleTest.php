<?php

use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\Customer\Models\Customer;

// ============================================================================
// Percentage Catalog Rule
// ============================================================================

it('should apply percentage catalog rule to bundle product for guest', function () {
    $product = $this->createBundleProduct([1000]);

    $product->load('bundle_options.bundle_option_products');
    $option = $product->bundle_options->first();
    $firstOptionProduct = $option->bundle_option_products->first();

    CatalogRule::factory()
        ->withIndex([1], [1, 2, 3])
        ->create(['status' => 1, 'action_type' => 'by_percent', 'discount_amount' => 20]);

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

    CatalogRule::factory()
        ->withIndex([1], [2])
        ->create(['status' => 1, 'action_type' => 'by_percent', 'discount_amount' => 25]);

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

    CatalogRule::factory()
        ->withIndex([1], [3])
        ->create(['status' => 1, 'action_type' => 'by_percent', 'discount_amount' => 30]);

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

    CatalogRule::factory()
        ->withIndex([1], [1, 2, 3])
        ->create(['status' => 1, 'action_type' => 'by_fixed', 'discount_amount' => 150]);

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

    CatalogRule::factory()
        ->withIndex([1], [2])
        ->create(['status' => 1, 'action_type' => 'by_fixed', 'discount_amount' => 200]);

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

    CatalogRule::factory()
        ->withIndex([1], [3])
        ->create(['status' => 1, 'action_type' => 'by_fixed', 'discount_amount' => 250]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, [
        'bundle_options' => [$option->id => [$firstOptionProduct->id]],
        'bundle_option_qty' => [$option->id => 1],
    ])->assertOk();

    $this->assertCartItemPrice($response, 750);
});
