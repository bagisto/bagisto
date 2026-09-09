<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Webkul\Core\Helpers\CacheGeneration;
use Webkul\Core\Models\CoreConfig;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Enums\SearchEngineEnum;
use Webkul\Product\Jobs\Search\DeleteProducts as DeleteSearchIndexJob;
use Webkul\Product\Jobs\Search\IndexProducts as IndexSearchJob;
use Webkul\Product\Services\Search\SearchEngineManager;

/**
 * Switch the catalog search engine to Elasticsearch for the current request.
 */
function useElasticSearchEngine(): void
{
    $settings = [
        SearchEngineManager::ENABLED_KEY => '1',
        SearchEngineManager::ENGINE_KEY => SearchEngineEnum::ELASTIC->value,
    ];

    foreach ($settings as $code => $value) {
        CoreConfig::query()->updateOrCreate(
            ['code' => $code, 'channel_code' => null, 'locale_code' => null],
            ['value' => $value]
        );
    }

    CacheGeneration::bump(CoreConfigRepository::class);
}

/**
 * Read the product ids a queued search index job was dispatched with.
 */
function searchJobProductIds($job): array
{
    return (function () {
        return $this->productIds;
    })->call($job);
}

it('reindexes the configurable parent instead of deleting it from the search index when a variant is deleted', function () {
    $configurable = (new ProductFaker)->getConfigurableProductFactory()->create();

    $variant = $configurable->variants->first();

    useElasticSearchEngine();

    Queue::fake();

    Event::dispatch('catalog.product.delete.before', $variant->id);

    Queue::assertPushed(
        DeleteSearchIndexJob::class,
        fn ($job) => searchJobProductIds($job) === [$variant->id],
    );

    Queue::assertPushed(
        IndexSearchJob::class,
        fn ($job) => in_array($configurable->id, searchJobProductIds($job)),
    );
});

it('does not include a surviving parent in the search index delete job when a variant is deleted', function () {
    $configurable = (new ProductFaker)->getConfigurableProductFactory()->create();

    $variant = $configurable->variants->first();

    useElasticSearchEngine();

    Queue::fake();

    Event::dispatch('catalog.product.delete.before', $variant->id);

    Queue::assertPushed(
        DeleteSearchIndexJob::class,
        fn ($job) => ! in_array($configurable->id, searchJobProductIds($job)),
    );
});

it('deletes the parent and every variant from the search index when the configurable itself is deleted', function () {
    $configurable = (new ProductFaker)->getConfigurableProductFactory()->create();

    $variantIds = $configurable->variants->pluck('id')->toArray();

    useElasticSearchEngine();

    Queue::fake();

    Event::dispatch('catalog.product.delete.before', $configurable->id);

    Queue::assertPushed(DeleteSearchIndexJob::class, function ($job) use ($configurable, $variantIds) {
        $ids = searchJobProductIds($job);

        return in_array($configurable->id, $ids)
            && empty(array_diff($variantIds, $ids));
    });

    Queue::assertNotPushed(IndexSearchJob::class);
});
