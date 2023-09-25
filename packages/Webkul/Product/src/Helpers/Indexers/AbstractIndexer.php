<?php

namespace Webkul\Product\Helpers\Indexers;

abstract class AbstractIndexer
{
    /**
     * Default batch size
     */
    protected const BATCH_SIZE = 100;

    /**
     * Special price from attribute id
     */
    protected const SPECIAL_PRICE_FROM_ATTRIBUTE_ID = 14;

    /**
     * Special price to attribute id
     */
    protected const SPECIAL_PRICE_TO_ATTRIBUTE_ID = 15;


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
    public function reindexRows($products)
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