<?php

namespace Webkul\Product\Listeners;

use Webkul\Product\Repositories\ProductBundleOptionProductRepository;
use Webkul\Product\Repositories\ProductGroupedProductRepository;
use Webkul\Product\Helpers\Indexer;

class Product
{
    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\Product\Repositories\ProductBundleOptionProductRepository  $productBundleOptionProductRepository
     * @param  \Webkul\Product\Repositories\ProductGroupedProductRepository  $productGroupedProductRepository
     * @param  \Webkul\Product\Helpers\Indexer  $indexer
     * @return void
     */
    public function __construct(
        protected ProductBundleOptionProductRepository $productBundleOptionProductRepository,
        protected ProductGroupedProductRepository $productGroupedProductRepository,
        protected Indexer $indexer
    )
    {
    }

    /**
     * Update or create product price indices
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterCreate($product)
    {
        $this->indexer->refreshFlat($product);
    }

    /**
     * Update or create product price indices
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterUpdate($product)
    {
        $this->indexer->refreshFlat($product);

        $this->refreshPriceIndices($product);

        $this->indexer->refreshInventory($product);
    }

    /**
     * Update or create product price indices
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function refreshPriceIndices($product)
    {
        $products = [$product];

        if ($product->type == 'simple') {
            if ($product->parent_id) {
                $products[] = $product->parent;
            }

            $products = array_merge(
                $products,
                $this->getParentBundleProducts($product),
                $this->getParentGroupProducts($product)
            );
        }

        foreach ($products as $product) {
            $this->indexer->refreshPrice($product);
        }
    }

    /**
     * Returns parents bundle products associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getParentBundleProducts($product)
    {
        $bundleOptionProducts = $this->productBundleOptionProductRepository->findWhere([
            'product_id' => $product->id,
        ]);

        $products = [];

        foreach ($bundleOptionProducts as $bundleOptionProduct) {
            $products[] = $bundleOptionProduct->bundle_option->product;
        }

        return $products;
    }

    /**
     * Returns parents group products associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getParentGroupProducts($product)
    {
        $groupedOptionProducts = $this->productGroupedProductRepository->findWhere([
            'associated_product_id' => $product->id,
        ]);

        $products = [];

        foreach ($groupedOptionProducts as $groupedOptionProduct) {
            $products[] = $groupedOptionProduct->product;
        }

        return $products;
    }
}
