<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Product\Models\Product;

use function Pest\Laravel\getJson;

/**
 * Create a category with a translation in the current locale.
 */
function createTestCategory(): Category
{
    return Category::factory()
        ->has(CategoryTranslation::factory(), 'translations')
        ->create();
}

/**
 * Create products in the category, named "<prefix> a", "<prefix> b", ... with the given prices and created a day apart.
 */
function createCategoryProducts(Category $category, array $prices = [100, 200, 300]): Collection
{
    $prefix = Str::lower(Str::random(8));

    $count = count($prices);

    return collect($prices)->map(function (float $price, int $index) use ($category, $prefix, $count) {
        $product = test()->createSimpleProduct([
            'name' => ['text_value' => $prefix.' '.chr(ord('a') + $index), 'locale' => app()->getLocale()],
            'price' => ['float_value' => $price],
        ]);

        $product->categories()->sync([$category->id]);

        Product::query()->whereKey($product->id)->update(['created_at' => now()->subDays($count - $index)]);

        return $product->fresh();
    });
}

/**
 * The ids of the products the storefront lists for the category in the given sort order.
 */
function listedProductIds(Category $category, string $sort): array
{
    return collect(getJson(route('shop.api.products.index', ['category_id' => $category->id, 'sort' => $sort]))
        ->assertOk()
        ->json('data'))
        ->pluck('id')
        ->all();
}

// ============================================================================
// Listing
// ============================================================================

it('should list the products of a category', function () {
    $category = createTestCategory();

    $product = $this->createSimpleProduct();

    $product->categories()->sync([$category->id]);

    getJson(route('shop.api.products.index', ['category_id' => $category->id]))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $product->id);
});

it('should leave out the products of other categories', function () {
    $category = createTestCategory();

    $product = $this->createSimpleProduct();

    $product->categories()->sync([createTestCategory()->id]);

    getJson(route('shop.api.products.index', ['category_id' => $category->id]))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

// ============================================================================
// Sorting
// ============================================================================

it('should sort the products of a category by name', function () {
    $category = createTestCategory();

    $products = createCategoryProducts($category);

    expect(listedProductIds($category, 'name-asc'))->toBe($products->pluck('id')->all())
        ->and(listedProductIds($category, 'name-desc'))->toBe($products->pluck('id')->reverse()->values()->all());
});

it('should sort the products of a category by the date they were created', function () {
    $category = createTestCategory();

    $products = createCategoryProducts($category);

    expect(listedProductIds($category, 'created_at-asc'))->toBe($products->pluck('id')->all())
        ->and(listedProductIds($category, 'created_at-desc'))->toBe($products->pluck('id')->reverse()->values()->all());
});

it('should sort the products of a category by price', function () {
    $category = createTestCategory();

    $products = createCategoryProducts($category, [300, 100, 200]);

    $byPrice = $products->sortBy(fn (Product $product) => $product->getTypeInstance()->getMinimalPrice())->pluck('id');

    expect(listedProductIds($category, 'price-asc'))->toBe($byPrice->values()->all())
        ->and(listedProductIds($category, 'price-desc'))->toBe($byPrice->reverse()->values()->all());
});
