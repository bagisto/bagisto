<?php

use Illuminate\Support\Facades\Event;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\CatalogRule\Repositories\CatalogRuleRepository;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Customer\Models\Customer;
use Webkul\Product\Helpers\Indexers\Price as PriceIndexer;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductPriceIndex;

/**
 * Create a category a catalog rule condition can name.
 */
function createCatalogRuleConditionCategory(): Category
{
    return Category::factory()
        ->has(CategoryTranslation::factory(), 'translations')
        ->create();
}

/**
 * Create a catalog rule taking 20% off the products whose categories meet the operator for the category.
 */
function createCategoryConditionCatalogRule($test, string $operator, Category $category): CatalogRule
{
    return $test->createCatalogRuleForPricing([
        'action_type' => 'by_percent',
        'discount_amount' => 20,
        'condition_type' => 1,
        'conditions' => [[
            'attribute' => 'product|category_ids',
            'operator' => $operator,
            'value' => [(string) $category->id],
            'attribute_type' => 'multiselect',
        ]],
    ], [1, 2, 3]);
}

/**
 * The price a guest sees a product listed at.
 */
function guestListedPrice(Product $product): float
{
    return (float) $product->fresh()->getTypeInstance()->getMinimalPrice();
}

// ============================================================================
// Percentage Catalog Rule
// ============================================================================

it('should apply percentage catalog rule to configurable variant for guest', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should apply percentage catalog rule to configurable variant for general customer', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 25], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 750);
});

it('should apply percentage catalog rule to configurable variant for wholesaler', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 30], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 700);
});

// ============================================================================
// Fixed Catalog Rule
// ============================================================================

it('should apply fixed catalog rule to configurable variant for guest', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 150], [1, 2, 3]);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 850);
});

it('should apply fixed catalog rule to configurable variant for general customer', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 200], [2]);

    $customer = Customer::factory()->create(['customer_group_id' => 2]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should apply fixed catalog rule to configurable variant for wholesaler', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    $this->createCatalogRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 250], [3]);

    $customer = Customer::factory()->create(['customer_group_id' => 3]);
    $this->loginAsCustomer($customer);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 750);
});

// ============================================================================
// Listed Price
// ============================================================================

it('should reprice the configurable product as soon as a catalog rule discounts its variants', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    expect((float) $product->fresh()->getTypeInstance()->getMinimalPrice())->toBe(800.0);
});

it('should restore the configurable product price as soon as the catalog rule is deleted', function () {
    $product = $this->createConfigurableProduct([1000]);

    $catalogRule = $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    ProductPriceIndex::where('product_id', $product->id)->update(['min_price' => 800]);

    Event::dispatch('promotions.catalog_rule.delete.before', $catalogRule->id);

    app(CatalogRuleRepository::class)->delete($catalogRule->id);

    expect((float) $product->fresh()->getTypeInstance()->getMinimalPrice())->toBe(1000.0);
});

it('should reprice the configurable product when the nightly price reindex reprices its variants', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    ProductPriceIndex::where('product_id', $product->id)->update(['min_price' => 1000]);

    app(PriceIndexer::class)->reindexSelective();

    expect((float) $product->fresh()->getTypeInstance()->getMinimalPrice())->toBe(800.0);
});

// ============================================================================
// Category And Inherited Conditions
// ============================================================================

it('should leave out the variants of a configurable product in a category a does not contain condition excludes', function () {
    $footwear = createCatalogRuleConditionCategory();

    $product = $this->createConfigurableProduct([1000]);

    $product->categories()->attach($footwear->id);

    createCategoryConditionCatalogRule($this, '!{}', $footwear);

    expect(guestListedPrice($product))->toBe(1000.0)
        ->and(guestListedPrice($product->variants->first()))->toBe(1000.0);
});

it('should discount the variants of a configurable product in a category a contains condition includes', function () {
    $footwear = createCatalogRuleConditionCategory();

    $product = $this->createConfigurableProduct([1000]);

    $product->categories()->attach($footwear->id);

    createCategoryConditionCatalogRule($this, '{}', $footwear);

    expect(guestListedPrice($product))->toBe(800.0)
        ->and(guestListedPrice($product->variants->first()))->toBe(800.0);
});

it('should leave out a variant placed in an excluded category even when its configurable product is not in it', function () {
    $footwear = createCatalogRuleConditionCategory();

    $product = $this->createConfigurableProduct([1000]);

    $variant = $product->variants->first();

    $variant->categories()->attach($footwear->id);

    createCategoryConditionCatalogRule($this, '!{}', $footwear);

    expect(guestListedPrice($variant))->toBe(1000.0);
});

it('should leave out a variant listed in another category when its configurable product is in an excluded category', function () {
    $footwear = createCatalogRuleConditionCategory();

    $sale = createCatalogRuleConditionCategory();

    $product = $this->createConfigurableProduct([1000]);

    $product->categories()->attach($footwear->id);

    $variant = $product->variants->first();

    $variant->categories()->attach($sale->id);

    createCategoryConditionCatalogRule($this, '!{}', $footwear);

    expect(guestListedPrice($variant))->toBe(1000.0);
});

it('should discount a variant placed in an included category even when its configurable product is not in it', function () {
    $sale = createCatalogRuleConditionCategory();

    $product = $this->createConfigurableProduct([1000]);

    $variant = $product->variants->first();

    $variant->categories()->attach($sale->id);

    createCategoryConditionCatalogRule($this, '{}', $sale);

    expect(guestListedPrice($variant))->toBe(800.0);
});

it('should match a variant by a value only its configurable product carries', function () {
    $product = $this->createConfigurableProduct([1000]);

    $variant = $product->variants->first();

    $productNumber = $this->getAttributeMap()['product_number'];

    ProductAttributeValue::where('product_id', $variant->id)
        ->where('attribute_id', $productNumber->id)
        ->delete();

    ProductAttributeValue::create([
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
    ], [1, 2, 3]);

    expect(guestListedPrice($variant))->toBe(800.0);
});

it('should reprice the variants when their configurable product moves into an excluded category', function () {
    $footwear = createCatalogRuleConditionCategory();

    $product = $this->createConfigurableProduct([1000]);

    createCategoryConditionCatalogRule($this, '!{}', $footwear);

    $product->categories()->attach($footwear->id);

    Event::dispatch('catalog.product.update.after', $product->fresh());

    expect(guestListedPrice($product))->toBe(1000.0);
});

it('should reprice a variant and its configurable product when the variant moves into an excluded category', function () {
    $footwear = createCatalogRuleConditionCategory();

    $product = $this->createConfigurableProduct([1000]);

    $variant = $product->variants->first();

    createCategoryConditionCatalogRule($this, '!{}', $footwear);

    $variant->categories()->attach($footwear->id);

    Event::dispatch('catalog.product.update.after', $variant->fresh());

    expect(guestListedPrice($variant))->toBe(1000.0)
        ->and(guestListedPrice($product))->toBe(1000.0);
});
