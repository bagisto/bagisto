<?php

use Webkul\Faker\Helpers\Category as CategoryFaker;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Shop\Helpers\CatalogApiCache;
use Webkul\Shop\Listeners\CatalogCache;
use Webkul\Shop\Providers\EventServiceProvider;

use function Pest\Laravel\getJson;

it('increments the catalog version when flushed', function () {
    $cache = app(CatalogApiCache::class);

    $version = $cache->version();

    $cache->flush();

    expect($cache->version())->toBe($version + 1);
});

it('counts every flush so invalidations are not collapsed', function () {
    $cache = app(CatalogApiCache::class);

    $version = $cache->version();

    $cache->flush();
    $cache->flush();
    $cache->flush();

    expect($cache->version())->toBe($version + 3);
});

it('caches a value until the catalog version is bumped', function () {
    $cache = app(CatalogApiCache::class);

    $first = $cache->remember('products', ['limit' => 10], fn () => 'first');
    $second = $cache->remember('products', ['limit' => 10], fn () => 'second');

    expect($first)->toBe('first')
        ->and($second)->toBe('first');

    $cache->flush();

    $third = $cache->remember('products', ['limit' => 10], fn () => 'third');

    expect($third)->toBe('third');
});

it('does not cache catalog responses for logged-in customers', function () {
    $cache = app(CatalogApiCache::class);

    expect($cache->shouldCache())->toBeTrue();

    $this->loginAsCustomer();

    expect($cache->shouldCache())->toBeFalse();

    $resolved = $cache->remember('products', [], fn () => 'fresh-each-time');

    expect($resolved)->toBe('fresh-each-time');
});

it('invalidates the catalog cache when a product is updated', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $cache = app(CatalogApiCache::class);

    $version = $cache->version();

    event('catalog.product.update.after', $product);

    expect($cache->version())->toBe($version + 1);
});

it('invalidates the catalog cache when a category is updated', function () {
    $category = (new CategoryFaker)->factory()->create();

    $cache = app(CatalogApiCache::class);

    $version = $cache->version();

    event('catalog.category.update.after', $category);

    expect($cache->version())->toBe($version + 1);
});

it('wires catalog cache invalidation for every event that can change a cached listing', function (string $event) {
    $listens = (new EventServiceProvider(app()))->listens();

    expect($listens)->toHaveKey($event)
        ->and($listens[$event])->toContain([CatalogCache::class, 'flush']);
})->with([
    'catalog.product.create.after',
    'catalog.product.update.after',
    'catalog.product.delete.before',
    'catalog.category.create.after',
    'catalog.category.update.after',
    'catalog.category.delete.before',
    'customer.review.update.after',
    'customer.review.delete.before',
    'checkout.order.save.after',
    'sales.order.cancel.after',
    'sales.refund.save.after',
]);

it('sends a public, cookie-varying cache-control header on the products listing for guests', function () {
    getJson(route('shop.api.products.index'))
        ->assertOk()
        ->assertHeader('Cache-Control', 'max-age=60, public')
        ->assertHeader('Vary', 'Cookie');
});

it('sends a public, cookie-varying cache-control header on the categories listing for guests', function () {
    getJson(route('shop.api.categories.index'))
        ->assertOk()
        ->assertHeader('Cache-Control', 'max-age=60, public')
        ->assertHeader('Vary', 'Cookie');
});

it('sends a private cache-control header on the products listing for customers', function () {
    $this->loginAsCustomer();

    getJson(route('shop.api.products.index'))
        ->assertOk()
        ->assertHeader('Cache-Control', 'no-cache, private');
});
