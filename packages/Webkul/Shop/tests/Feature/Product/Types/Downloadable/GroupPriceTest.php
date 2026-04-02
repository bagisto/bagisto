<?php

use Illuminate\Support\Facades\Event;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductCustomerGroupPrice;

/**
 * Create a customer group price and re-index the downloadable product so the price index
 * reflects the new group price.
 */
function setDownloadableGroupPrice(Product $product, int $customerGroupId, string $valueType, float $value, int $qty = 1): void
{
    ProductCustomerGroupPrice::factory()->create([
        'qty' => $qty,
        'value_type' => $valueType,
        'value' => $value,
        'product_id' => $product->id,
        'customer_group_id' => $customerGroupId,
    ]);

    // Re-index the product so the price index picks up the new group price.
    Event::dispatch('catalog.product.update.after', $product);
}

// ============================================================================
// Fixed Price Type
// ============================================================================

it('should apply fixed group price for guest customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    setDownloadableGroupPrice($product, customerGroupId: 1, valueType: 'fixed', value: 700);

    $response = $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $this->assertCartItemPrice($response, 700);
});

it('should apply fixed group price for general customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    setDownloadableGroupPrice($product, customerGroupId: 2, valueType: 'fixed', value: 600);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $this->assertCartItemPrice($response, 600);
});

it('should apply fixed group price for wholesale customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    setDownloadableGroupPrice($product, customerGroupId: 3, valueType: 'fixed', value: 500);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    $this->assertCartItemPrice($response, 500);
});

// ============================================================================
// Discount Percentage Type
// ============================================================================

it('should apply percentage group discount for guest customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    setDownloadableGroupPrice($product, customerGroupId: 1, valueType: 'discount', value: 20);

    $response = $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    // 1000 - (1000 * 20 / 100) = 800
    $this->assertCartItemPrice($response, 800);
});

it('should apply percentage group discount for general customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    setDownloadableGroupPrice($product, customerGroupId: 2, valueType: 'discount', value: 30);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    // 1000 - (1000 * 30 / 100) = 700
    $this->assertCartItemPrice($response, 700);
});

it('should apply percentage group discount for wholesale customer on downloadable product', function () {
    $product = $this->createDownloadableProduct(['price' => ['float_value' => 1000]], [0]);

    setDownloadableGroupPrice($product, 3, 'discount', 40);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, ['links' => $product->downloadable_links->pluck('id')->toArray()])->assertOk();

    // 1000 - (1000 * 40 / 100) = 600
    $this->assertCartItemPrice($response, 600);
});
