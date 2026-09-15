<?php

namespace Webkul\FPC\Listeners;

use Spatie\ResponseCache\Facades\ResponseCache;

class Price extends Product
{
    /**
     * The most products whose pages are dropped one by one; above it every page is dropped instead.
     */
    public const PER_PRODUCT_FORGET_LIMIT = 100;

    /**
     * After product prices are reindexed, drop the pages of the products whose prices changed, or every page
     * when no product ids are given because every price was reindexed, or when too many changed to walk.
     *
     * @param  array|null  $productIds
     * @return void
     */
    public function afterReindex($productIds = null)
    {
        if (! config('responsecache.enabled')) {
            return;
        }

        if (
            is_null($productIds)
            || count($productIds) > self::PER_PRODUCT_FORGET_LIMIT
        ) {
            ResponseCache::clear();

            return;
        }

        $urls = [];

        $products = $this->productRepository
            ->with(['attribute_family', 'attribute_values', 'categories.translations', 'parent'])
            ->findWhereIn('id', $productIds);

        foreach ($products as $product) {
            $urls = array_merge($urls, $this->getForgettableUrls($product));
        }

        $this->forgetPages($urls);
    }
}
