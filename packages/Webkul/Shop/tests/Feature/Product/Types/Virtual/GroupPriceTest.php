<?php

use Illuminate\Support\Facades\Event;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductCustomerGroupPrice;

/**
 * Create a customer group price and re-index the virtual product.
 */
function setVirtualGroupPrice(Product $product, int $customerGroupId, string $valueType, float $value): void
{
    ProductCustomerGroupPrice::factory()->create([
        'qty' => 1,
        'value_type' => $valueType,
        'value' => $value,
        'product_id' => $product->id,
        'customer_group_id' => $customerGroupId,
    ]);

    Event::dispatch('catalog.product.update.after', $product);
}

// ============================================================================
// Fixed Price Type
// ============================================================================

it('should apply fixed group price for guest on virtual product', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    setVirtualGroupPrice($product, 1, 'fixed', 350);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 350);
});

it('should apply fixed group price for general customer on virtual product', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    setVirtualGroupPrice($product, 2, 'fixed', 300);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 300);
});

it('should apply fixed group price for wholesale customer on virtual product', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    setVirtualGroupPrice($product, 3, 'fixed', 250);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 250);
});

// ============================================================================
// Discount Percentage Type
// ============================================================================

it('should apply percentage group discount for guest on virtual product', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    setVirtualGroupPrice($product, 1, 'discount', 20);

    $response = $this->addProductToCart($product->id)->assertOk();

    // 500 - (500 * 20 / 100) = 400
    $this->assertCartItemPrice($response, 400);
});

it('should apply percentage group discount for general customer on virtual product', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    setVirtualGroupPrice($product, 2, 'discount', 30);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    // 500 - (500 * 30 / 100) = 350
    $this->assertCartItemPrice($response, 350);
});

it('should apply percentage group discount for wholesale customer on virtual product', function () {
    $product = $this->createVirtualProduct(['price' => ['float_value' => 500]]);

    setVirtualGroupPrice($product, 3, 'discount', 40);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    // 500 - (500 * 40 / 100) = 300
    $this->assertCartItemPrice($response, 300);
});
