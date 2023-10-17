<?php

namespace Webkul\Admin\Helpers\Reporting;

use Illuminate\Database\Eloquent\Collection;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;
use Webkul\Sales\Repositories\OrderItemRepository;

class Product extends AbstractReporting
{
    /**
     * Create a helper instance.
     * 
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Product\Repositories\ProductInventoryRepository  $productInventoryRepository
     * @param  \Webkul\Sales\Repositories\OrderItemRepository  $orderItemRepository
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductInventoryRepository $productInventoryRepository,
        protected OrderItemRepository $orderItemRepository,
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

    /**
     * Gets top-selling products.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopSellingProducts(): collection
    {
        $topSellingProducts = $this->orderItemRepository->getTopSellingProductsByDate($this->startDate, $this->endDate);

        foreach ($topSellingProducts as $orderItem) {
            $orderItem->formatted_total = core()->formatBasePrice($orderItem->total);

            $orderItem->formatted_price = core()->formatBasePrice($orderItem->price);
        }

        return $topSellingProducts;
    }
}