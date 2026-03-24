<?php

namespace Webkul\Product\Services\Search\Indexers;

use Webkul\Product\Contracts\SearchIndexer;
use Webkul\Product\Helpers\Indexers\ElasticSearch;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Services\Search\Engines\ElasticSearchEngine;

class ElasticSearchIndexer implements SearchIndexer
{
    /**
     * Create a new instance.
     */
    public function __construct(
        protected ElasticSearch $elasticSearchIndexer,
        protected ProductRepository $productRepository,
    ) {}

    /**
     * Index a batch of products via the existing ElasticSearch indexer.
     */
    public function indexBatch(array $products): void
    {
        $this->elasticSearchIndexer->reindexRows($products);
    }

    /**
     * Remove products from all channel/locale indices.
     */
    public function deleteBatch(array $productIds): void
    {
        $removeIndices = [];

        foreach (core()->getAllChannels() as $channel) {
            foreach ($channel->locales as $locale) {
                $index = ElasticSearchEngine::formatIndexName($channel->code, $locale->code);

                $removeIndices[$index] = $productIds;
            }
        }

        $this->elasticSearchIndexer->deleteIndices($removeIndices);
    }

    /**
     * Full reindex of all products.
     */
    public function reindexFull(): void
    {
        $this->elasticSearchIndexer->reindexFull();
    }
}
