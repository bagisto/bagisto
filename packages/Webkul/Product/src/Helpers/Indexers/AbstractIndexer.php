<?php

namespace Webkul\Product\Helpers\Indexers;

abstract class AbstractIndexer
{
    /**
     * Default batch size
     */
    protected const BATCH_SIZE = 100;

    public function reindexSelective()
    {
    }

    public function reindexRows(array $products)
    {
        $currentBatch = [];

        $i = 0;

        foreach ($products as $product) {
            $currentBatch[] = $product;

            if (++$i === self::BATCH_SIZE) {
                $this->insertBatch($currentBatch);

                $i = 0;

                $currentBatch = [];
            }
        }

        if (! empty($currentBatch)) {
            $this->insertBatch($currentBatch);
        }
    }

    public function reindexRow($product)
    {
        return $this->reindexRows([$product]);
    }
}