<?php

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Webkul\Core\Helpers\CacheGeneration;
use Webkul\Core\Models\CoreConfig;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Faker\Helpers\Product as ProductFaker;
use Webkul\Product\Jobs\ElasticSearch\DeleteIndex as DeleteElasticSearchIndexJob;
use Webkul\Product\Jobs\ElasticSearch\UpdateCreateIndex as UpdateCreateElasticSearchIndexJob;

/**
 * Switch the catalog search engine to Elasticsearch for the current request.
 */
function useElasticSearchEngine(): void
{
    CoreConfig::query()->updateOrCreate(
        ['code' => 'catalog.products.search.engine', 'channel_code' => null, 'locale_code' => null],
        ['value' => 'elastic']
    );

    CacheGeneration::bump(CoreConfigRepository::class);
}

/**
 * Read the product ids a queued Elasticsearch job was dispatched with.
 */
function elasticSearchJobProductIds($job): array
{
    return (function () {
        return $this->productIds;
    })->call($job);
}

it('reindexes the configurable parent instead of deleting it from Elasticsearch when a variant is deleted', function () {
    $configurable = (new ProductFaker)->getConfigurableProductFactory()->create();

    $variant = $configurable->variants->first();

    useElasticSearchEngine();

    Queue::fake();

    Event::dispatch('catalog.product.delete.before', $variant->id);

    Queue::assertPushed(
        DeleteElasticSearchIndexJob::class,
        fn ($job) => elasticSearchJobProductIds($job) === [$variant->id],
    );

    Queue::assertPushed(
        UpdateCreateElasticSearchIndexJob::class,
        fn ($job) => in_array($configurable->id, elasticSearchJobProductIds($job)),
    );
});

it('does not include a surviving parent in the Elasticsearch delete job when a variant is deleted', function () {
    $configurable = (new ProductFaker)->getConfigurableProductFactory()->create();

    $variant = $configurable->variants->first();

    useElasticSearchEngine();

    Queue::fake();

    Event::dispatch('catalog.product.delete.before', $variant->id);

    Queue::assertPushed(
        DeleteElasticSearchIndexJob::class,
        fn ($job) => ! in_array($configurable->id, elasticSearchJobProductIds($job)),
    );
});

it('deletes the parent and every variant from Elasticsearch when the configurable itself is deleted', function () {
    $configurable = (new ProductFaker)->getConfigurableProductFactory()->create();

    $variantIds = $configurable->variants->pluck('id')->toArray();

    useElasticSearchEngine();

    Queue::fake();

    Event::dispatch('catalog.product.delete.before', $configurable->id);

    Queue::assertPushed(DeleteElasticSearchIndexJob::class, function ($job) use ($configurable, $variantIds) {
        $ids = elasticSearchJobProductIds($job);

        return in_array($configurable->id, $ids)
            && empty(array_diff($variantIds, $ids));
    });

    Queue::assertNotPushed(UpdateCreateElasticSearchIndexJob::class);
});
