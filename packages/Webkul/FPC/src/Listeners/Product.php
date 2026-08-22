<?php

namespace Webkul\FPC\Listeners;

use Webkul\FPC\Concerns\ForgetsPages;
use Webkul\Product\Repositories\ProductBundleOptionProductRepository;
use Webkul\Product\Repositories\ProductGroupedProductRepository;
use Webkul\Product\Repositories\ProductRepository;

class Product
{
    use ForgetsPages;

    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductBundleOptionProductRepository $productBundleOptionProductRepository,
        protected ProductGroupedProductRepository $productGroupedProductRepository
    ) {}

    /**
     * Update or create product page cache
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterCreate($product)
    {
        $this->forgetPages($this->getForgettableUrls($product));
    }

    /**
     * Update or create product page cache
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function afterUpdate($product)
    {
        $this->forgetPages($this->getForgettableUrls($product));
    }

    /**
     * Delete product page c
     *
     * @param  int  $productId
     * @return void
     */
    public function beforeDelete($productId)
    {
        $product = $this->productRepository->find($productId);

        if (! $product) {
            return;
        }

        $this->forgetPages($this->getForgettableUrls($product));
    }

    /**
     * Returns product urls
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getForgettableUrls($product)
    {
        $urls = [$this->homePath()];

        $products = $this->getAllRelatedProducts($product);

        foreach ($products as $related) {
            if ($related?->url_key) {
                $urls[] = '/'.$related->url_key;
            }

            $urls = array_merge($urls, $this->getCategoryUrls($related));
        }

        return $urls;
    }

    /**
     * The listing pages a product is shown on.
     *
     * A product is drawn on its category pages and in the home page carousels as well as at its
     * own address, so dropping only its own page leaves the price, image and name it had before
     * on every listing that carries it.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     */
    protected function getCategoryUrls($product): array
    {
        $urls = [];

        foreach ($product?->categories ?? [] as $category) {
            foreach (core()->getAllLocales() as $locale) {
                if ($translation = $category->translate($locale->code)) {
                    $urls[] = '/'.$translation->slug;
                }
            }
        }

        return $urls;
    }

    /**
     * Returns parents bundle products associated with simple product
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getAllRelatedProducts($product)
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
        } elseif ($product->type == 'configurable') {
            $products = [];

            /**
             * Fetching fresh variants.
             */
            foreach ($product->variants()->get() as $variant) {
                $products[] = $variant;
            }

            $products[] = $product;
        }

        return $products;
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
