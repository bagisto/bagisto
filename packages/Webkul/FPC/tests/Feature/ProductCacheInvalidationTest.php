<?php

use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Core\Models\Locale;
use Webkul\FPC\Listeners\Product as ProductListener;
use Webkul\Product\Models\Product;

/**
 * Put the product in a category carrying the given slug, and return that category.
 */
function categorise(Product $product, string $slug, string $locale = 'en'): Category
{
    $category = Category::factory()->create();

    CategoryTranslation::factory()->create([
        'category_id' => $category->id,
        'locale' => $locale,
        'locale_id' => Locale::query()->where('code', $locale)->value('id'),
        'slug' => $slug,
    ]);

    $product->categories()->attach($category->id);

    return $category;
}

beforeEach(function () {
    $this->useIsolatedPageCache();

    $this->listener = app(ProductListener::class);
});

// ============================================================================
// Created And Updated Products
// ============================================================================

it('should drop the product page and the home page when a product is created', function () {
    $product = $this->createSimpleProduct();

    $home = $this->cachePage('/');

    $page = $this->cachePage('/'.$product->url_key);

    $this->listener->afterCreate($product);

    $this->assertPageNotCached($page);

    $this->assertPageNotCached($home, 'A new product is drawn in the home page carousels.');
});

it('should drop the listing pages a product appears on when it is updated', function () {
    $product = $this->createSimpleProduct();

    categorise($product, 'summer-sale');

    $listing = $this->cachePage('/summer-sale');

    $this->listener->afterUpdate($product->refresh());

    $this->assertPageNotCached($listing, 'The category listing kept the price and image the product had before.');
});

// ============================================================================
// Deleted Products
// ============================================================================

it('should drop the product pages before the product is deleted', function () {
    $product = $this->createSimpleProduct();

    $home = $this->cachePage('/');

    $page = $this->cachePage('/'.$product->url_key);

    $this->listener->beforeDelete($product->id);

    $this->assertPageNotCached($page);

    $this->assertPageNotCached($home);
});

it('should do nothing when the product being deleted is already gone', function () {
    $home = $this->cachePage('/');

    $this->listener->beforeDelete(0);

    $this->assertPageCached($home);
});

// ============================================================================
// Forgettable Urls
// ============================================================================

it('should list the home page and every listing the product is on as forgettable', function () {
    $product = $this->createSimpleProduct();

    categorise($product, 'summer-sale');

    $urls = $this->listener->getForgettableUrls($product->refresh());

    expect($urls)->toContain('/')
        ->toContain('/'.$product->url_key)
        ->toContain('/summer-sale');
});
