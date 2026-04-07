<?php

use Webkul\Customer\Models\Customer;

// ============================================================================
// Fixed Price Type
// ============================================================================

it('should apply fixed group price for guest on grouped product', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $associated = $product->grouped_products->first()->associated_product;
    $this->setCustomerGroupPrice($associated, 1, 'fixed', 700);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    // The first associated product should have the group price applied.
    $this->assertCartItemPrice($response, 700, 0);
});

it('should apply fixed group price for general customer on grouped product', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $associated = $product->grouped_products->first()->associated_product;
    $this->setCustomerGroupPrice($associated, 2, 'fixed', 600);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 600, 0);
});

it('should apply fixed group price for wholesale customer on grouped product', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $associated = $product->grouped_products->first()->associated_product;
    $this->setCustomerGroupPrice($associated, 3, 'fixed', 500);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 500, 0);
});

// ============================================================================
// Discount Percentage Type
// ============================================================================

it('should apply percentage group discount for guest on grouped product', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $associated = $product->grouped_products->first()->associated_product;
    $this->setCustomerGroupPrice($associated, 1, 'discount', 20);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    // 1000 - (1000 * 20 / 100) = 800
    $this->assertCartItemPrice($response, 800, 0);
});

it('should apply percentage group discount for general customer on grouped product', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $associated = $product->grouped_products->first()->associated_product;
    $this->setCustomerGroupPrice($associated, 2, 'discount', 30);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    // 1000 - (1000 * 30 / 100) = 700
    $this->assertCartItemPrice($response, 700, 0);
});

it('should apply percentage group discount for wholesale customer on grouped product', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $associated = $product->grouped_products->first()->associated_product;
    $this->setCustomerGroupPrice($associated, 3, 'discount', 40);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    // 1000 - (1000 * 40 / 100) = 600
    $this->assertCartItemPrice($response, 600, 0);
});
