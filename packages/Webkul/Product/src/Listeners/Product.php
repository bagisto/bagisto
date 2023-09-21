<?php

namespace Webkul\Product\Listeners;

use Illuminate\Support\Facades\Bus;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductBundleOptionProductRepository;
use Webkul\Product\Repositories\ProductGroupedProductRepository;
use Webkul\Product\Helpers\Indexers\Flat as FlatIndexer;
use Webkul\Product\Jobs\UpdateCreateInventoryIndex as UpdateCreateInventoryIndexJob;
use Webkul\Product\Jobs\UpdateCreatePriceIndex as UpdateCreatePriceIndexJob;
use Webkul\Product\Jobs\ElasticSearch\UpdateCreateIndex as UpdateCreateElasticSearchIndexJob;
use Webkul\Product\Jobs\ElasticSearch\DeleteIndex as DeleteElasticSearchIndexJob;

class Product
{
    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Product\Repositories\ProductBundleOptionProductRepository  $productBundleOptionProductRepository
     * @param  \Webkul\Product\Repositories\ProductGroupedProductRepository  $productGroupedProductRepository
     * @param  \Webkul\Product\Helpers\Indexers\Flat  $flatIndexer
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductBundleOptionProductRepository $productBundleOptionProductRepository,
        protected ProductGroupedProductRepository $productGroupedProductRepository,
        protected FlatIndexer $flatIndexer
    )
    {
    }

    /**
     * Update or create product indices
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterCreate($product)
    {
        $this->flatIndexer->refresh($product);
    }

    /**
     * Update or create product indices
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterUpdate($product)
    {
        $this->flatIndexer->refresh($product);

        $productIds = $this->getAllRelatedProductIds($product);

        Bus::chain([
            new UpdateCreateInventoryIndexJob($productIds),
            new UpdateCreatePriceIndexJob($productIds),
            new UpdateCreateElasticSearchIndexJob($productIds),
        ])->dispatch();
    }

    /**
     * Delete product indices
     *
     * @param  integer  $productId
     * @return void
     */
    public function beforeDelete($productId)
    {
        if (core()->getConfigData('catalog.products.storefront.search_mode') != 'elastic') {
            return;
        }

        DeleteElasticSearchIndexJob::dispatch($productId);
    }

    /**
     * Returns parents bundle product ids associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getAllRelatedProductIds($product)
    {
        $productIds = [$product->id];

        if ($product->type == 'simple') {
            if ($product->parent_id) {
                $productIds[] = $product->parent_id;
            }

            $productIds = array_merge(
                $productIds,
                $this->getParentBundleProductIds($product),
                $this->getParentGroupProductIds($product)
            );
        } elseif ($product->type == 'configurable') {
            $productIds = [
                ...$product->variants->pluck('id')->toArray(),
                ...$productIds
            ];
        }

        return $productIds;
    }

    /**
     * Returns parents bundle product ids associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getParentBundleProductIds($product)
    {
        $bundleOptionProducts = $this->productBundleOptionProductRepository->findWhere([
            'product_id' => $product->id,
        ]);

        $productIds = [];

        foreach ($bundleOptionProducts as $bundleOptionProduct) {
            $productIds[] = $bundleOptionProduct->bundle_option->product_id;
        }

        return $productIds;
    }

    /**
     * Returns parents group product ids associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getParentGroupProductIds($product)
    {
        $groupedOptionProducts = $this->productGroupedProductRepository->findWhere([
            'associated_product_id' => $product->id,
        ]);

        return $groupedOptionProducts->pluck('product_id')->toArray();
    }
}
