<?php

use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\Customer\Models\Customer;

/**
 * Add a grouped product to the cart with qty 1 for each associated product.
 */
function addGroupedToCartForCatalogRule($test, $product, int $quantity = 1)
{
    $product->load('grouped_products');

    $qty = [];

    foreach ($product->grouped_products as $gp) {
        $qty[$gp->associated_product_id] = $quantity;
    }

    return $test->addProductToCart($product->id, 1, ['qty' => $qty]);
}

// ============================================================================
// Percentage Catalog Rule
// ============================================================================

it('should apply percentage catalog rule to grouped product for guest', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    CatalogRule::factory()
        ->withIndex([1], [1, 2, 3])
        ->create(['status' => 1, 'action_type' => 'by_percent', 'discount_amount' => 20]);

    $response = addGroupedToCartForCatalogRule($this, $product)->assertOk();

    // 1000 - (1000 * 20 / 100) = 800
    $this->assertCartItemPrice($response, 800, 0);
});

it('should apply percentage catalog rule to grouped product for general customer', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    CatalogRule::factory()
        ->withIndex([1], [2])
        ->create(['status' => 1, 'action_type' => 'by_percent', 'discount_amount' => 25]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = addGroupedToCartForCatalogRule($this, $product)->assertOk();

    // 1000 - (1000 * 25 / 100) = 750
    $this->assertCartItemPrice($response, 750, 0);
});

it('should apply percentage catalog rule to grouped product for wholesaler', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    CatalogRule::factory()
        ->withIndex([1], [3])
        ->create(['status' => 1, 'action_type' => 'by_percent', 'discount_amount' => 30]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = addGroupedToCartForCatalogRule($this, $product)->assertOk();

    // 1000 - (1000 * 30 / 100) = 700
    $this->assertCartItemPrice($response, 700, 0);
});

// ============================================================================
// Fixed Catalog Rule
// ============================================================================

it('should apply fixed catalog rule to grouped product for guest', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    CatalogRule::factory()
        ->withIndex([1], [1, 2, 3])
        ->create(['status' => 1, 'action_type' => 'by_fixed', 'discount_amount' => 150]);

    $response = addGroupedToCartForCatalogRule($this, $product)->assertOk();

    // 1000 - 150 = 850
    $this->assertCartItemPrice($response, 850, 0);
});

it('should apply fixed catalog rule to grouped product for general customer', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    CatalogRule::factory()
        ->withIndex([1], [2])
        ->create(['status' => 1, 'action_type' => 'by_fixed', 'discount_amount' => 200]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = addGroupedToCartForCatalogRule($this, $product)->assertOk();

    // 1000 - 200 = 800
    $this->assertCartItemPrice($response, 800, 0);
});

it('should apply fixed catalog rule to grouped product for wholesaler', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    CatalogRule::factory()
        ->withIndex([1], [3])
        ->create(['status' => 1, 'action_type' => 'by_fixed', 'discount_amount' => 250]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = addGroupedToCartForCatalogRule($this, $product)->assertOk();

    // 1000 - 250 = 750
    $this->assertCartItemPrice($response, 750, 0);
});
