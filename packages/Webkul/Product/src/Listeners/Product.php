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

        $this->refreshInventoryIndices($product);

        $this->refreshPriceIndices($product);
    }

    /**
     * Update or create product price indices
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function refreshPriceIndices($product)
    {
        $products = $this->getAllRelatedProducts($product);

        foreach ($products as $product) {
            $this->indexer->refreshPrice($product);
        }
    }

    /**
     * Update or create product inventory indices
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function refreshInventoryIndices($product)
    {
        $products = $this->getAllRelatedProducts($product);

        foreach ($products as $product) {
            $this->indexer->refreshInventory($product);
        }
    }

    /**
     * Returns parents bundle products associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getAllRelatedProducts($product)
    {
        static $products = [];

        if (array_key_exists($product->id, $products)) {
            return $products[$product->id];
        }

        $products[$product->id] = [$product];

        if ($product->type == 'simple') {
            if ($product->parent_id) {
                $products[$product->id][] = $product->parent;
            }

            $products[$product->id] = array_merge(
                $products[$product->id],
                $this->getParentBundleProducts($product),
                $this->getParentGroupProducts($product)
            );
        } elseif ($product->type == 'configurable') {
            foreach ($product->variants as $variant) {
                $products[$product->id][] = $variant;
            }
        }

        return $products[$product->id];
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
