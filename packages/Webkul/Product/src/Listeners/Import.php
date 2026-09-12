<?php

namespace Webkul\Product\Listeners;

use Illuminate\Support\Arr;
use Webkul\Product\Helpers\Indexers\Flat as FlatIndexer;
use Webkul\Product\Repositories\ProductRepository;

class Import
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected FlatIndexer $flatIndexer
    ) {}

    /**
     * Write the admin locale's flat rows for an indexed batch of imported products, whose own flat
     * rows carry only the locales the file names.
     *
     * @return void
     */
    public function afterBatchIndexing($batch)
    {
        $products = $this->productRepository
            ->with([
                'channels',
                'attribute_family',
                'attribute_values',
                'variants',
                'variants.channels',
                'variants.attribute_family',
                'variants.attribute_values',
            ])
            ->findWhereIn('sku', Arr::pluck($batch->data, 'sku'));

        $this->flatIndexer->refreshAdminLocale($products->all());
    }
}
