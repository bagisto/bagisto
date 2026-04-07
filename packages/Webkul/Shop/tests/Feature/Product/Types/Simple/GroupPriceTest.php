<?php

use Webkul\Customer\Models\Customer;

// ============================================================================
// Fixed Price Type
// ============================================================================

it('should apply fixed group price for guest customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->setCustomerGroupPrice($product, customerGroupId: 1, valueType: 'fixed', value: 700);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 700);
});

it('should apply fixed group price for general customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->setCustomerGroupPrice($product, customerGroupId: 2, valueType: 'fixed', value: 600);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 600);
});

it('should apply fixed group price for wholesale customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->setCustomerGroupPrice($product, customerGroupId: 3, valueType: 'fixed', value: 500);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 500);
});

// ============================================================================
// Discount Percentage Type
// ============================================================================

it('should apply percentage group discount for guest customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->setCustomerGroupPrice($product, customerGroupId: 1, valueType: 'discount', value: 20);

    $response = $this->addProductToCart($product->id)->assertOk();

    // 1000 - (1000 * 20 / 100) = 800
    $this->assertCartItemPrice($response, 800);
});

it('should apply percentage group discount for general customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->setCustomerGroupPrice($product, customerGroupId: 2, valueType: 'discount', value: 30);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    // 1000 - (1000 * 30 / 100) = 700
    $this->assertCartItemPrice($response, 700);
});

it('should apply percentage group discount for wholesale customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->setCustomerGroupPrice($product, 3, 'discount', 40);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    // 1000 - (1000 * 40 / 100) = 600
    $this->assertCartItemPrice($response, 600);
});
