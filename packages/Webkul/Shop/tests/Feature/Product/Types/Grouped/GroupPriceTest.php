<?php

use Illuminate\Support\Facades\Event;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductCustomerGroupPrice;

/**
 * Set a group price on the first associated product of a grouped product
 * and re-index so the price index reflects the new group price.
 */
function setGroupedGroupPrice(Product $product, int $customerGroupId, string $valueType, float $value, int $qty = 1): void
{
    $product->load('grouped_products');

    $firstAssociated = Product::find($product->grouped_products->first()->associated_product_id);

    ProductCustomerGroupPrice::factory()->create([
        'qty' => $qty,
        'value_type' => $valueType,
        'value' => $value,
        'product_id' => $firstAssociated->id,
        'customer_group_id' => $customerGroupId,
    ]);

    // Re-index the associated product so the price index picks up the new group price.
    Event::dispatch('catalog.product.update.after', $firstAssociated);
}

/**
 * Add a grouped product to the cart with qty 1 for each associated product.
 */
function addGroupedToCartForGroupPrice($test, $product, int $quantity = 1)
{
    $product->load('grouped_products');

    $qty = [];

    foreach ($product->grouped_products as $gp) {
        $qty[$gp->associated_product_id] = $quantity;
    }

    return $test->addProductToCart($product->id, 1, ['qty' => $qty]);
}

// ============================================================================
// Fixed Price Type
// ============================================================================

it('should apply fixed group price for guest on grouped product', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    setGroupedGroupPrice($product, customerGroupId: 1, valueType: 'fixed', value: 700);

    $response = addGroupedToCartForGroupPrice($this, $product)->assertOk();

    // The first associated product should have the group price applied.
    $this->assertCartItemPrice($response, 700, 0);
});

it('should apply fixed group price for general customer on grouped product', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    setGroupedGroupPrice($product, customerGroupId: 2, valueType: 'fixed', value: 600);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = addGroupedToCartForGroupPrice($this, $product)->assertOk();

    $this->assertCartItemPrice($response, 600, 0);
});

it('should apply fixed group price for wholesale customer on grouped product', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    setGroupedGroupPrice($product, customerGroupId: 3, valueType: 'fixed', value: 500);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = addGroupedToCartForGroupPrice($this, $product)->assertOk();

    $this->assertCartItemPrice($response, 500, 0);
});

// ============================================================================
// Discount Percentage Type
// ============================================================================

it('should apply percentage group discount for guest on grouped product', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    setGroupedGroupPrice($product, customerGroupId: 1, valueType: 'discount', value: 20);

    $response = addGroupedToCartForGroupPrice($this, $product)->assertOk();

    // 1000 - (1000 * 20 / 100) = 800
    $this->assertCartItemPrice($response, 800, 0);
});

it('should apply percentage group discount for general customer on grouped product', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    setGroupedGroupPrice($product, customerGroupId: 2, valueType: 'discount', value: 30);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = addGroupedToCartForGroupPrice($this, $product)->assertOk();

    // 1000 - (1000 * 30 / 100) = 700
    $this->assertCartItemPrice($response, 700, 0);
});

it('should apply percentage group discount for wholesale customer on grouped product', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    setGroupedGroupPrice($product, customerGroupId: 3, valueType: 'discount', value: 40);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = addGroupedToCartForGroupPrice($this, $product)->assertOk();

    // 1000 - (1000 * 40 / 100) = 600
    $this->assertCartItemPrice($response, 600, 0);
});
