<?php

use Illuminate\Pagination\Cursor;
use Webkul\Product\Helpers\Indexers\Price as PriceIndexer;
use Webkul\Product\Models\ProductPriceIndex;

// ============================================================================
// Discounts
// ============================================================================

it('should apply a percentage catalog rule to a simple product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 800);
})->with('customer groups');

it('should apply a fixed catalog rule to a simple product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 150], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 850);
})->with('customer groups');

it('should not apply a catalog rule limited to another customer group to a simple product', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [3]);

    $this->actAsCustomerGroup(2);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});

// ============================================================================
// Batched Reindexing
// ============================================================================

it('should reprice the products of a saved catalog rule when an earlier batched reindex left its cursor in the request', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    request()->query->add(['cursor' => (new Cursor(['products.id' => $product->id]))->encode()]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20]);

    expect($this->listedPrice($product))->toBePrice(800);
});

it('should reprice every product in a full price reindex when an earlier batched reindex left its cursor in the request', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 1000]]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20]);

    ProductPriceIndex::query()->where('product_id', $product->id)->update(['min_price' => 1000]);

    request()->query->add(['cursor' => (new Cursor(['products.id' => $product->id]))->encode()]);

    app(PriceIndexer::class)->reindexFull();

    expect($this->listedPrice($product))->toBePrice(800);
});
