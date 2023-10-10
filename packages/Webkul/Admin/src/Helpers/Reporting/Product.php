<?php

namespace Webkul\Admin\Helpers\Reporting;

use Illuminate\Database\Eloquent\Collection;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;

class Product extends AbstractReporting
{
    /**
     * Create a helper instance.
     * 
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Product\Repositories\ProductInventoryRepository  $productInventoryRepository
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductInventoryRepository $productInventoryRepository
    )
    {
        parent::__construct();
    }

    /**
     * Gets stock threshold.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getStockThreshold(): Collection
    {
        return $this->productInventoryRepository->getStockThreshold();
    }
}