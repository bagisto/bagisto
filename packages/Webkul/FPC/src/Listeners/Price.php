<?php

namespace Webkul\FPC\Listeners;

use Spatie\ResponseCache\Facades\ResponseCache;

class Price extends Product
{
    /**
     * After product prices are reindexed, drop the pages of the products whose prices changed, or every
     * page when no product ids are given because every price was reindexed.
     *
     * @param  array|null  $productIds
     * @return void
     */
    public function afterReindex($productIds = null)
    {
        if (is_null($productIds)) {
            ResponseCache::clear();

            return;
        }

        $urls = [];

        foreach ($this->productRepository->findWhereIn('id', $productIds) as $product) {
            $urls = array_merge($urls, $this->getForgettableUrls($product));
        }

        $this->forgetPages($urls);
    }
}
