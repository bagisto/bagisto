<?php

use Illuminate\Support\Facades\Event;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Repositories\ProductRepository;

beforeEach(function () {
    $this->useIsolatedPageCache();

    $this->product = (new ProductFaker)->getSimpleProductFactory()->create();

    $this->productPage = $this->cachePage('/'.$this->product->url_key);

    $this->homePage = $this->cachePage('/');

    config(['responsecache.enabled' => false]);
});

/**
 * Assert the given cached pages survived, reading the cache with it switched back on.
 */
function assertPagesSurvived($test, array $requests): void
{
    config(['responsecache.enabled' => true]);

    foreach ($requests as $request) {
        $test->assertPageCached($request, 'The page '.$request->getPathInfo().' was dropped while the page cache was disabled.');
    }
}

it('should keep every cached page when a full price reindex is announced while the page cache is disabled', function () {
    Event::dispatch('catalog.product.price.reindex.after');

    assertPagesSurvived($this, [$this->productPage, $this->homePage]);
});

it('should keep the pages of reindexed products, without looking them up, when a price reindex is announced while the page cache is disabled', function (string $event) {
    $products = $this->spy(ProductRepository::class);

    Event::dispatch($event, [[$this->product->id]]);

    assertPagesSurvived($this, [$this->productPage, $this->homePage]);

    $products->shouldNotHaveReceived('with');

    $products->shouldNotHaveReceived('findWhereIn');
})->with([
    'a catalog rule reindex' => ['promotions.catalog_rule.reindex.after'],
    'a selective price reindex' => ['catalog.product.price.reindex.after'],
]);
