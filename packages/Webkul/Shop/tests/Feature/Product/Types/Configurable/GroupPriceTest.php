<?php

use Webkul\Customer\Models\Customer;

// ============================================================================
// Fixed Price Type
// ============================================================================

it('should apply fixed group price for guest on configurable variant', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    $this->setCustomerGroupPrice($variant, 1, 'fixed', 700);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 700);
});

it('should apply fixed group price for general customer on configurable variant', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    $this->setCustomerGroupPrice($variant, 2, 'fixed', 600);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 600);
});

it('should apply fixed group price for wholesale customer on configurable variant', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    $this->setCustomerGroupPrice($variant, 3, 'fixed', 500);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 500);
});

// ============================================================================
// Discount Percentage Type
// ============================================================================

it('should apply percentage group discount for guest on configurable variant', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    $this->setCustomerGroupPrice($variant, 1, 'discount', 20);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should apply percentage group discount for general customer on configurable variant', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    $this->setCustomerGroupPrice($variant, 2, 'discount', 30);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 700);
});

it('should apply percentage group discount for wholesale customer on configurable variant', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    $this->setCustomerGroupPrice($variant, 3, 'discount', 40);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 600);
});
