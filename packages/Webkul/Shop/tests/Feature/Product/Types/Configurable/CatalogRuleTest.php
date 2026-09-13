<?php

use Illuminate\Support\Facades\Event;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\CatalogRule\Repositories\CatalogRuleRepository;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Product\Helpers\Indexers\Price as PriceIndexer;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductPriceIndex;

/**
 * Create a category a catalog rule condition can name.
 */
function catalogRuleCategory(): Category
{
    return Category::factory()
        ->has(CategoryTranslation::factory(), 'translations')
        ->create();
}

/**
 * Create a catalog rule taking 20% off the products whose categories meet the operator for the category.
 */
function categoryConditionCatalogRule(string $operator, Category $category): CatalogRule
{
    return test()->createCatalogRuleForPricing([
        'action_type' => 'by_percent',
        'discount_amount' => 20,
        'condition_type' => 1,
        'conditions' => [[
            'attribute' => 'product|category_ids',
            'operator' => $operator,
            'value' => [(string) $category->id],
            'attribute_type' => 'multiselect',
        ]],
    ]);
}

// ============================================================================
// Discounts
// ============================================================================

it('should apply a percentage catalog rule to a configurable variant', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800);
})->with('customer groups');

it('should apply a fixed catalog rule to a configurable variant', function (array $ruleGroups, ?int $customerGroupId) {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 150], $ruleGroups);

    $this->actAsCustomerGroup($customerGroupId);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 850);
})->with('customer groups');

it('should not apply a catalog rule limited to another customer group to a configurable variant', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [3]);

    $this->actAsCustomerGroup(2);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});

// ============================================================================
// Listed Price
// ============================================================================

it('should reprice the configurable product as soon as a catalog rule discounts its variants', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20]);

    expect($this->listedPrice($product))->toBePrice(800);
});

it('should restore the configurable product price as soon as the catalog rule is deleted', function () {
    $product = $this->createConfigurableProduct([1000]);

    $catalogRule = $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20]);

    ProductPriceIndex::query()->where('product_id', $product->id)->update(['min_price' => 800]);

    Event::dispatch('promotions.catalog_rule.delete.before', $catalogRule->id);

    app(CatalogRuleRepository::class)->delete($catalogRule->id);

    expect($this->listedPrice($product))->toBePrice(1000);
});

it('should reprice the configurable product when the nightly price reindex reprices its variants', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20]);

    ProductPriceIndex::query()->where('product_id', $product->id)->update(['min_price' => 1000]);

    app(PriceIndexer::class)->reindexSelective();

    expect($this->listedPrice($product))->toBePrice(800);
});

// ============================================================================
// Category And Inherited Conditions
// ============================================================================

it('should leave out the variants of a configurable product in a category a does not contain condition excludes', function () {
    $footwear = catalogRuleCategory();

    $product = $this->createConfigurableProduct([1000]);

    $product->categories()->attach($footwear->id);

    categoryConditionCatalogRule('!{}', $footwear);

    expect($this->listedPrice($product))->toBePrice(1000)
        ->and($this->listedPrice($product->variants->first()))->toBePrice(1000);
});

it('should discount the variants of a configurable product in a category a contains condition includes', function () {
    $footwear = catalogRuleCategory();

    $product = $this->createConfigurableProduct([1000]);

    $product->categories()->attach($footwear->id);

    categoryConditionCatalogRule('{}', $footwear);

    expect($this->listedPrice($product))->toBePrice(800)
        ->and($this->listedPrice($product->variants->first()))->toBePrice(800);
});

it('should leave out a variant placed in an excluded category even when its configurable product is not in it', function () {
    $footwear = catalogRuleCategory();

    $product = $this->createConfigurableProduct([1000]);

    $variant = $product->variants->first();

    $variant->categories()->attach($footwear->id);

    categoryConditionCatalogRule('!{}', $footwear);

    expect($this->listedPrice($variant))->toBePrice(1000);
});

it('should leave out a variant listed in another category when its configurable product is in an excluded category', function () {
    $footwear = catalogRuleCategory();

    $sale = catalogRuleCategory();

    $product = $this->createConfigurableProduct([1000]);

    $product->categories()->attach($footwear->id);

    $variant = $product->variants->first();

    $variant->categories()->attach($sale->id);

    categoryConditionCatalogRule('!{}', $footwear);

    expect($this->listedPrice($variant))->toBePrice(1000);
});

it('should discount a variant placed in an included category even when its configurable product is not in it', function () {
    $sale = catalogRuleCategory();

    $product = $this->createConfigurableProduct([1000]);

    $variant = $product->variants->first();

    $variant->categories()->attach($sale->id);

    categoryConditionCatalogRule('{}', $sale);

    expect($this->listedPrice($variant))->toBePrice(800);
});

it('should match a variant by a value only its configurable product carries', function () {
    $product = $this->createConfigurableProduct([1000]);

    $variant = $product->variants->first();

    $productNumber = $this->getAttributeMap()['product_number'];

    ProductAttributeValue::query()
        ->where('product_id', $variant->id)
        ->where('attribute_id', $productNumber->id)
        ->delete();

    ProductAttributeValue::query()->create([
        'product_id' => $product->id,
        'attribute_id' => $productNumber->id,
        'text_value' => $number = 'PN-'.$product->id,
        'unique_id' => $product->id.'|'.$productNumber->id,
    ]);

    $this->createCatalogRuleForPricing([
        'action_type' => 'by_percent',
        'discount_amount' => 20,
        'condition_type' => 1,
        'conditions' => [[
            'attribute' => 'product|product_number',
            'operator' => '==',
            'value' => $number,
            'attribute_type' => 'text',
        ]],
    ]);

    expect($this->listedPrice($variant))->toBePrice(800);
});

it('should reprice the variants when their configurable product moves into an excluded category', function () {
    $footwear = catalogRuleCategory();

    $product = $this->createConfigurableProduct([1000]);

    categoryConditionCatalogRule('!{}', $footwear);

    $product->categories()->attach($footwear->id);

    Event::dispatch('catalog.product.update.after', $product->fresh());

    expect($this->listedPrice($product))->toBePrice(1000);
});

it('should reprice a variant and its configurable product when the variant moves into an excluded category', function () {
    $footwear = catalogRuleCategory();

    $product = $this->createConfigurableProduct([1000]);

    $variant = $product->variants->first();

    categoryConditionCatalogRule('!{}', $footwear);

    $variant->categories()->attach($footwear->id);

    Event::dispatch('catalog.product.update.after', $variant->fresh());

    expect($this->listedPrice($variant))->toBePrice(1000)
        ->and($this->listedPrice($product))->toBePrice(1000);
});
