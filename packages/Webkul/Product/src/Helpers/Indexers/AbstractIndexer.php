<?php

namespace Webkul\Product\Helpers\Indexers;

abstract class AbstractIndexer
{
    /**
     * Default batch size
     */
    protected const BATCH_SIZE = 100;

    abstract public function reindexBatch(array $products);

    /**
     * Reindex all products
     *
     * @return void
     */
    public function reindexFull()
    {
    }

    /**
     * Reindex necessary products
     *
     * @return void
     */
    public function reindexSelective()
    {
        return $this->reindexFull();
    }

    /**
     * Reindex products by preparing batches
     *
     * @param  array  $products
     * @return void
     */
    public function reindexRows(array $products)
    {
        $currentBatch = [];

        $i = 0;

        foreach ($products as $product) {
            $currentBatch[] = $product;

            if (++$i === self::BATCH_SIZE) {
                $this->reindexBatch($currentBatch);

                $i = 0;

                $currentBatch = [];
            }
        }

        if (! empty($currentBatch)) {
            $this->reindexBatch($currentBatch);
        }
    }

    /**
     * Reindex single product
     *
     * @param  array  $products
     * @return void
     */
    public function reindexRow($product)
    {
        $this->reindexBatch([$product]);
    }
}