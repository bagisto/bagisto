<?php

use Illuminate\Support\Facades\View;
use Webkul\Category\Models\Category;
use Webkul\Category\Models\CategoryTranslation;

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

// ============================================================================
// Paths Under The Admin URL
// ============================================================================

it('should answer a path under the admin URL that no admin route matches as not found, showing no storefront page', function (string $path) {
    $shown = false;

    View::composer(['shop::home.index', 'shop::categories.view', 'shop::products.view'], function () use (&$shown) {
        $shown = true;
    });

    get('/'.trim(config('app.admin_url'), '/').'/'.$path)
        ->assertNotFound();

    expect($shown)->toBeFalse();
})->with([
    'path naming a storefront category' => [fn () => Category::factory()->has(CategoryTranslation::factory(), 'translations')->create()->slug],
    'path the slug pattern rejects' => ['report.html'],
]);

// ============================================================================
// Malformed Ids On The Storefront's Own Routes
// ============================================================================

it('should answer a URL template a storefront page prints as not found, showing no storefront page', function (string $path) {
    $shown = false;

    View::composer(['shop::home.index', 'shop::categories.view', 'shop::products.view'], function () use (&$shown) {
        $shown = true;
    });

    get($path)->assertNotFound();

    expect($shown)->toBeFalse();
})->with([
    'review translation' => ['api/product/1/reviews/:reviewId/translate'],
    'booking slots' => ['booking-slots/:id'],
]);

it('should answer a non-numeric product, review, attribute or booking product id as not found', function (string $path) {
    getJson($path)->assertNotFound();
})->with([
    'review list' => ['api/product/abc/reviews'],
    'review translation' => ['api/product/1/reviews/abc/translate'],
    'attribute options' => ['api/categories/attributes/abc/options'],
    'booking slots' => ['booking-slots/abc'],
]);

it('should still answer a numeric product or attribute id', function () {
    getJson(route('shop.api.products.reviews.index', $this->createSimpleProduct()->id))
        ->assertOk()
        ->assertJsonStructure(['data']);

    getJson(route('shop.api.categories.attribute_options', $this->getAttributeMap()['color']->id))
        ->assertOk()
        ->assertJsonStructure(['data']);
});

// ============================================================================
// Paths That Are No Slug
// ============================================================================

it('should answer a path the slug pattern rejects as not found, showing no home page', function (string $path) {
    $shown = false;

    View::composer('shop::home.index', function () use (&$shown) {
        $shown = true;
    });

    get($path)->assertNotFound();

    expect($shown)->toBeFalse();
})->with([
    'file name' => ['wp-login.php'],
    'well-known file' => ['.well-known/security.txt'],
    'url template placeholder' => ['product/:slug'],
]);

it('should still show the home page at the root', function () {
    get(route('shop.home.index'))->assertOk();
});
