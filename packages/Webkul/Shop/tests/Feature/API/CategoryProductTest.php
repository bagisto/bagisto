<?php

use Illuminate\Support\Collection;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;

use function Pest\Laravel\getJson;

/**
 * Create a category with translation for testing.
 */
function createTestCategory(): Category
{
    return Category::factory()
        ->has(CategoryTranslation::factory(), 'translations')
        ->create();
}

/**
 * Create multiple products attached to a category.
 */
function createCategoryProducts($testContext, Category $category, int $count = 3): Collection
{
    $products = collect();

    for ($i = 0; $i < $count; $i++) {
        $product = $testContext->createSimpleProduct();
        $product->categories()->sync([$category->id]);
        $products->push($product);
    }

    return $products;
}

// ============================================================================
// Listing
// ============================================================================

it('should return category products', function () {
    $category = createTestCategory();

    $product = $this->createSimpleProduct();
    $product->categories()->sync([$category->id]);

    getJson(route('shop.api.products.index', ['category_id' => $category->id]))
        ->assertOk()
        ->assertJsonFragment(['id' => $product->id]);
});

// ============================================================================
// Sort by Name
// ============================================================================

it('should return category products sorted by name descending', function () {
    $category = createTestCategory();
    $products = createCategoryProducts($this, $category);

    $expected = $products->pluck('name')->sortDesc()->values()->toArray();

    getJson(route('shop.api.products.index', ['category_id' => $category->id, 'sort' => 'name-desc']))
        ->assertOk()
        ->assertSeeTextInOrder($expected);
});

it('should return category products sorted by name ascending', function () {
    $category = createTestCategory();
    $products = createCategoryProducts($this, $category);

    $expected = $products->pluck('name')->sort()->values()->toArray();

    getJson(route('shop.api.products.index', ['category_id' => $category->id, 'sort' => 'name-asc']))
        ->assertOk()
        ->assertSeeTextInOrder($expected);
});

// ============================================================================
// Sort by Date
// ============================================================================

it('should return category products sorted by created_at descending', function () {
    $category = createTestCategory();
    $products = createCategoryProducts($this, $category);

    getJson(route('shop.api.products.index', ['category_id' => $category->id, 'sort' => 'created_at-desc']))
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

it('should return category products sorted by created_at ascending', function () {
    $category = createTestCategory();
    $products = createCategoryProducts($this, $category);

    getJson(route('shop.api.products.index', ['category_id' => $category->id, 'sort' => 'created_at-asc']))
        ->assertOk()
        ->assertJsonCount(3, 'data');
});

// ============================================================================
// Sort by Price
// ============================================================================

it('should return category products sorted by price descending', function () {
    $category = createTestCategory();
    $products = createCategoryProducts($this, $category);

    $expected = $products
        ->map(fn ($p) => $p->getTypeInstance()->getMinimalPrice())
        ->sortDesc()
        ->map(fn ($price) => core()->formatPrice($price))
        ->toArray();

    getJson(route('shop.api.products.index', ['category_id' => $category->id, 'sort' => 'price-desc']))
        ->assertOk()
        ->assertSeeTextInOrder($expected);
});

it('should return category products sorted by price ascending', function () {
    $category = createTestCategory();
    $products = createCategoryProducts($this, $category);

    $expected = $products
        ->map(fn ($p) => $p->getTypeInstance()->getMinimalPrice())
        ->sort()
        ->map(fn ($price) => core()->formatPrice($price))
        ->toArray();

    getJson(route('shop.api.products.index', ['category_id' => $category->id, 'sort' => 'price-asc']))
        ->assertOk()
        ->assertSeeTextInOrder($expected);
});
