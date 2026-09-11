<?php

use Illuminate\Support\Facades\Event;
use Webkul\CatalogRule\Helpers\CatalogRuleIndex;
use Webkul\CatalogRule\Jobs\DeleteCatalogRuleIndex;
use Webkul\CatalogRule\Jobs\UpdateCreateCatalogRuleIndex;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Helpers\Indexers\Price as PriceIndexer;
use Webkul\Product\Repositories\ProductPriceIndexRepository;
use Webkul\Product\Repositories\ProductRepository;

beforeEach(function () {
    $this->useIsolatedPageCache();

    $this->otherHostScope = $this->addChannelOnHost('shop-two.test');

    $this->secondScope = $this->addSecondScope();

    $this->product = (new ProductFaker)->getSimpleProductFactory()->create();

    $this->otherProduct = (new ProductFaker)->getSimpleProductFactory()->create();
});

/**
 * Cache a price-bearing path for a guest in the current scope, a second locale and currency, and a channel on its own host.
 */
function cachePriceBearingPage($test, string $path): array
{
    return [
        $test->cachePage($path),
        $test->cachePage($path, $test->secondScope),
        $test->cachePage($path, $test->otherHostScope, 'shop-two.test'),
    ];
}

/**
 * Record each announcement of a reindex, with what it carried, into the given sequence.
 */
function recordReindexAnnouncements(string $event, array &$sequence): void
{
    foreach (['before', 'after'] as $moment) {
        Event::listen($event.'.'.$moment, function (...$payload) use ($moment, &$sequence) {
            $sequence[] = [$moment, $payload];
        });
    }
}

/**
 * A real price indexer whose batches are only recorded, so its full and selective runs can be observed.
 */
function priceIndexerRecordingBatches(array &$sequence): PriceIndexer
{
    $indexer = Mockery::mock(PriceIndexer::class.'[reindexBatch]', [
        app(CustomerGroupRepository::class),
        app(ProductRepository::class),
        app(ProductPriceIndexRepository::class),
    ]);

    $indexer->shouldReceive('reindexBatch')->andReturnUsing(function () use (&$sequence) {
        $sequence[] = ['reindex'];
    });

    return $indexer;
}

it('drops the pages of the products whose prices were reindexed everywhere, and leaves other products alone', function (string $event) {
    $copies = [
        ...cachePriceBearingPage($this, '/'.$this->product->url_key),
        ...cachePriceBearingPage($this, '/'),
    ];

    $otherProductPage = $this->cachePage('/'.$this->otherProduct->url_key);

    Event::dispatch($event, [[$this->product->id]]);

    foreach ($copies as $copy) {
        $this->assertPageNotCached($copy, 'A copy of '.$copy->getPathInfo().' on '.$copy->getHost().' kept the old price.');
    }

    $this->assertPageCached($otherProductPage, 'A product whose price was not reindexed lost its cached page.');
})->with([
    'a catalog rule reindex' => ['promotions.catalog_rule.reindex.after'],
    'a selective price reindex' => ['catalog.product.price.reindex.after'],
]);

it('clears every page when every product price was reindexed', function () {
    $otherProductPage = $this->cachePage('/'.$this->otherProduct->url_key);

    Event::dispatch('catalog.product.price.reindex.after');

    $this->assertPageNotCached($otherProductPage, 'A full price reindex left a page with a price that may have changed.');
});

it('announces a saved catalog rule only after the prices of its products are reindexed', function () {
    $sequence = [];

    $catalogRule = CatalogRule::factory()->make(['status' => 0]);

    $catalogRule->setRelation('catalog_rule_products', collect([(object) ['product_id' => $this->product->id]]));

    $this->mock(CatalogRuleIndex::class)->shouldReceive('cleanProductIndices')->once();

    $this->mock(PriceIndexer::class)
        ->shouldReceive('reindexBatch')
        ->andReturnUsing(function ($products) use (&$sequence) {
            $sequence[] = ['reindex', collect($products)->pluck('id')->all()];
        });

    recordReindexAnnouncements('promotions.catalog_rule.reindex', $sequence);

    (new UpdateCreateCatalogRuleIndex($catalogRule))->handle();

    expect($sequence)->toBe([
        ['before', [[$this->product->id]]],
        ['reindex', [$this->product->id]],
        ['after', [[$this->product->id]]],
    ]);
});

it('announces a removed catalog rule only after the prices of its products are reindexed', function () {
    $sequence = [];

    $this->mock(PriceIndexer::class)
        ->shouldReceive('reindexBatch')
        ->andReturnUsing(function ($products) use (&$sequence) {
            $sequence[] = ['reindex', collect($products)->pluck('id')->all()];
        });

    recordReindexAnnouncements('promotions.catalog_rule.reindex', $sequence);

    (new DeleteCatalogRuleIndex([$this->product->id]))->handle();

    expect($sequence)->toBe([
        ['before', [[$this->product->id]]],
        ['reindex', [$this->product->id]],
        ['after', [[$this->product->id]]],
    ]);
});

it('announces a full price reindex after it runs, without product ids', function () {
    $sequence = [];

    $indexer = priceIndexerRecordingBatches($sequence);

    recordReindexAnnouncements('catalog.product.price.reindex', $sequence);

    $indexer->reindexFull();

    expect($sequence[0])->toBe(['before', []])
        ->and(end($sequence))->toBe(['after', []])
        ->and($sequence)->toContain(['reindex']);
});

it('announces a selective price reindex after it runs, with the ids it reindexed', function () {
    $sequence = [];

    $indexer = priceIndexerRecordingBatches($sequence);

    recordReindexAnnouncements('catalog.product.price.reindex', $sequence);

    $indexer->reindexSelective();

    $after = end($sequence);

    expect($sequence[0])->toBe(['before', []])
        ->and($after[0])->toBe('after')
        ->and($after[1])->toHaveCount(1)
        ->and($after[1][0])->toBeArray();
});
