<?php

use Illuminate\Pagination\Cursor;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Helpers\Indexers\Price as PriceIndexer;
use Webkul\Product\Models\ProductPriceIndex;

// ============================================================================
// Percentage Catalog Rule
// ============================================================================

it('should apply percentage catalog rule discount for guest', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should apply percentage catalog rule discount for general customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 25], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 750);
});

it('should apply percentage catalog rule discount for wholesaler', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 30], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 700);
});

// ============================================================================
// Fixed Catalog Rule
// ============================================================================

it('should apply fixed catalog rule discount for guest', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 150], [1, 2, 3]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 850);
});

it('should apply fixed catalog rule discount for general customer', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 200], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should apply fixed catalog rule discount for wholesaler', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 250], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 750);
});

// ============================================================================
// Batched Reindexing
// ============================================================================

it('should reprice the products of a saved catalog rule when an earlier batched reindex left its cursor in the request', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    request()->query->add(['cursor' => (new Cursor(['products.id' => $product->id]))->encode()]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    expect((float) $product->fresh()->getTypeInstance()->getMinimalPrice())->toBe(800.0);
});

it('should reprice every product in a full price reindex when an earlier batched reindex left its cursor in the request', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    ProductPriceIndex::where('product_id', $product->id)->update(['min_price' => 1000]);

    request()->query->add(['cursor' => (new Cursor(['products.id' => $product->id]))->encode()]);

    app(PriceIndexer::class)->reindexFull();

    expect((float) $product->fresh()->getTypeInstance()->getMinimalPrice())->toBe(800.0);
});
