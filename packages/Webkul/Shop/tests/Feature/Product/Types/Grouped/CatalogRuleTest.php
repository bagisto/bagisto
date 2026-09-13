<?php

use Illuminate\Support\Facades\Event;
use Webkul\CatalogRule\Repositories\CatalogRuleRepository;
use Webkul\Product\Helpers\Indexers\Price as PriceIndexer;
use Webkul\Product\Models\ProductPriceIndex;

// ============================================================================
// Discounts
// ============================================================================

it('should apply a percentage catalog rule to the associated products of a grouped product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800, 0)
        ->assertCartItemPrice($response, 400, 1);
})->with('customer groups');

it('should apply a fixed catalog rule to the associated products of a grouped product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 150], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 850, 0)
        ->assertCartItemPrice($response, 350, 1);
})->with('customer groups');

it('should not apply a catalog rule limited to another customer group to a grouped product', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [3]);

    $this->actAsCustomerGroup(2);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 1000, 0);
});

// ============================================================================
// Listed Price
// ============================================================================

it('should reprice the grouped product as soon as a catalog rule discounts its associated products', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20]);

    expect($this->listedPrice($product))->toBePrice(400);
});

it('should restore the grouped product price as soon as the catalog rule is deleted', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $catalogRule = $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20]);

    ProductPriceIndex::query()->where('product_id', $product->id)->update(['min_price' => 400]);

    Event::dispatch('promotions.catalog_rule.delete.before', $catalogRule->id);

    app(CatalogRuleRepository::class)->delete($catalogRule->id);

    expect($this->listedPrice($product))->toBePrice(500);
});

it('should reprice the grouped product when the nightly price reindex reprices its associated products', function () {
    $product = $this->createGroupedProduct([1000, 500]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20]);

    ProductPriceIndex::query()->where('product_id', $product->id)->update(['min_price' => 500]);

    app(PriceIndexer::class)->reindexSelective();

    expect($this->listedPrice($product))->toBePrice(400);
});
