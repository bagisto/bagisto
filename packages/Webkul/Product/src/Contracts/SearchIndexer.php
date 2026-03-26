<?php

namespace Webkul\Product\Contracts;

interface SearchIndexer
{
    /**
     * Index a batch of products.
     */
    public function indexBatch(array $products): void;

    /**
     * Remove products from the search index.
     */
    public function deleteBatch(array $productIds): void;

    /**
     * Full reindex of all products.
     */
    public function reindexFull(): void;
}
