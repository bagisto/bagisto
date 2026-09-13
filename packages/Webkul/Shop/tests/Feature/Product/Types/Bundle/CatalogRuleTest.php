<?php

use Illuminate\Support\Facades\Event;
use Webkul\CatalogRule\Repositories\CatalogRuleRepository;
use Webkul\Product\Helpers\Indexers\Price as PriceIndexer;
use Webkul\Product\Models\ProductPriceIndex;

// ============================================================================
// Discounts
// ============================================================================

it('should apply a percentage catalog rule to the option products of a bundle product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createBundleProduct([1000]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800);
})->with('customer groups');

it('should apply a fixed catalog rule to the option products of a bundle product', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createBundleProduct([1000]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 150], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 850);
})->with('customer groups');

it('should not apply a catalog rule limited to another customer group to a bundle product', function () {
    $product = $this->createBundleProduct([1000]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [3]);

    $this->actAsCustomerGroup(2);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});

// ============================================================================
// Listed Price
// ============================================================================

it('should reprice the bundle product as soon as a catalog rule discounts its option products', function () {
    $product = $this->createBundleProduct([1000]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20]);

    expect($this->listedPrice($product))->toBePrice(800);
});

it('should restore the bundle product price as soon as the catalog rule is deleted', function () {
    $product = $this->createBundleProduct([1000]);

    $catalogRule = $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20]);

    ProductPriceIndex::query()->where('product_id', $product->id)->update(['min_price' => 800]);

    Event::dispatch('promotions.catalog_rule.delete.before', $catalogRule->id);

    app(CatalogRuleRepository::class)->delete($catalogRule->id);

    expect($this->listedPrice($product))->toBePrice(1000);
});

it('should reprice the bundle product when the nightly price reindex reprices its option products', function () {
    $product = $this->createBundleProduct([1000]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20]);

    ProductPriceIndex::query()->where('product_id', $product->id)->update(['min_price' => 1000]);

    app(PriceIndexer::class)->reindexSelective();

    expect($this->listedPrice($product))->toBePrice(800);
});
