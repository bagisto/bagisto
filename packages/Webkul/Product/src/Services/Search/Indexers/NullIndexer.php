<?php

namespace Webkul\Product\Services\Search\Indexers;

use Webkul\Product\Contracts\SearchIndexer;

class NullIndexer implements SearchIndexer
{
    /**
     * No-op when no external search engine is configured.
     */
    public function indexBatch(array $products): void {}

    /**
     * No-op when no external search engine is configured.
     */
    public function deleteBatch(array $productIds): void {}

    /**
     * No-op when no external search engine is configured.
     */
    public function reindexFull(): void {}
}
