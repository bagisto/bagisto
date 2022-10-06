<?php

namespace Webkul\Product\Helpers;

use Webkul\Customer\Repositories\CustomerGroupRepository;
use Webkul\Product\Repositories\ProductPriceIndexRepository;

class PriceIndexer
{
    /**
     * Create a new command instance.
     *
     * @param  \Webkul\Customer\Repositories\CustomerGroupRepository  $customerGroupRepository
     * @param  \Webkul\Product\Repositories\ProductPriceIndexRepository  $productPriceIndexRepository
     * @return void
     */
    public function __construct(
        protected CustomerGroupRepository $customerGroupRepository,
        protected ProductPriceIndexRepository $productPriceIndexRepository
    )
    {
    }

    /**
     * Refresh product price indexes
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function refresh($product)
    {
        $indexer = app($product->getTypeInstance()->getPriceIndexer())
            ->setProduct($product);

        $customerGroups = $this->customerGroupRepository->all();

        foreach ($customerGroups as $customerGroup) {
            $this->productPriceIndexRepository->updateOrCreate([
                'customer_group_id' => $customerGroup->id,
                'product_id'        => $product->id,
            ], $indexer->getIndices($customerGroup));
        }
    }
}