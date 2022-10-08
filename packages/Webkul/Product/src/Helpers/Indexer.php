<?php

namespace Webkul\Product\Helpers;

use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Product\Repositories\ProductPriceIndexRepository;
use Webkul\Product\Repositories\ProductInventoryIndexRepository;
use Webkul\Product\Helpers\Indexers\Flat\Product as FlatIndexer;

class Indexer
{
    /**
     * Create a new command instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerGroupRepository  $customerGroupRepository
     * @param  \Webkul\Product\Repositories\ProductPriceIndexRepository  $productPriceIndexRepository
     * @param  \Webkul\Product\Repositories\ProductInventoryIndexRepository  $productInventoryIndexRepository
     * @param  \Webkul\Product\Helpers\Indexers\Flat\Product  $flatIndexer
     * @return void
     */
    public function __construct(
        protected CustomerGroupRepository $customerGroupRepository,
        protected ProductPriceIndexRepository $productPriceIndexRepository,
        protected ProductInventoryIndexRepository $productInventoryIndexRepository,
        protected FlatIndexer $flatIndexer
    )
    {
    }

    /**
     * Refresh product indexes
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function refresh($product)
    {
        $this->refreshPrice($product);

        $this->refreshInventory($product);
    }

    /**
     * Refresh product flat indexes
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function refreshFlat($product)
    {
        $this->flatIndexer->refresh($product);
    }

    /**
     * Refresh product price indexes
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function refreshPrice($product)
    {
        $indexer = $product->getTypeInstance()
            ->getPriceIndexer()
            ->setProduct($product);

        $customerGroups = $this->customerGroupRepository->all();

        foreach ($customerGroups as $customerGroup) {
            $this->productPriceIndexRepository->updateOrCreate([
                'customer_group_id' => $customerGroup->id,
                'product_id'        => $product->id,
            ], $indexer->getIndices($customerGroup));
        }
    }

    /**
     * Refresh product inventory indices
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function refreshInventory($product)
    {
    }
}