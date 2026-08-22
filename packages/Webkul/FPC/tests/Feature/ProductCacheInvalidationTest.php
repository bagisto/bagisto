<?php

use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;
use Webkul\Core\Models\Locale;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\FPC\Listeners\Product as ProductListener;
use Webkul\Product\Models\Product;

/**
 * A simple product, built the way the rest of the suite builds one.
 */
function simpleProduct(): Product
{
    return (new ProductFaker)->getSimpleProductFactory()->create();
}

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

it('drops the product page and the home page when a product is created', function () {
    // Arrange
    $product = simpleProduct();

    $home = $this->cachePage('/');

    $page = $this->cachePage('/'.$product->url_key);

    // Act
    $this->listener->afterCreate($product);

    // Assert
    $this->assertPageNotCached($page);

    $this->assertPageNotCached($home, 'A new product is drawn in the home page carousels.');
});

it('drops the listing pages a product appears on when it is updated', function () {
    // Arrange
    $product = simpleProduct();

    categorise($product, 'summer-sale');

    $listing = $this->cachePage('/summer-sale');

    // Act
    $this->listener->afterUpdate($product->refresh());

    // Assert
    $this->assertPageNotCached($listing, 'The category listing kept the price and image the product had before.');
});

it('drops the product pages before the product is deleted', function () {
    // Arrange
    $product = simpleProduct();

    $home = $this->cachePage('/');

    $page = $this->cachePage('/'.$product->url_key);

    // Act
    $this->listener->beforeDelete($product->id);

    // Assert
    $this->assertPageNotCached($page);

    $this->assertPageNotCached($home);
});

it('does nothing when the product being deleted is already gone', function () {
    // Arrange
    $home = $this->cachePage('/');

    // Act
    $this->listener->beforeDelete(0);

    // Assert
    $this->assertPageCached($home);
});

it('lists the home page and every listing the product is on as forgettable', function () {
    // Arrange
    $product = simpleProduct();

    categorise($product, 'summer-sale');

    // Act
    $urls = $this->listener->getForgettableUrls($product->refresh());

    // Assert
    expect($urls)->toContain('/');

    expect($urls)->toContain('/'.$product->url_key);

    expect($urls)->toContain('/summer-sale');
});
