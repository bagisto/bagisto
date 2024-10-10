<?php

namespace Webkul\Sitemap\Models;

use Illuminate\Support\Carbon;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Webkul\Product\Models\Product as BaseProduct;

class Product extends BaseProduct implements Sitemapable
{
    /**
     * To get the sitemap tag for the product.
     */
    public function toSitemapTag(): Url|string|array
    {
        if (
            ! $this->url_key
            || ! $this->status
            || ! $this->visible_individually
        ) {
            return [];
        }

        return Url::create(route('shop.product_or_category.index', $this->url_key))
            ->setLastModificationDate(Carbon::create($this->updated_at))
            ->setChangeFrequency(core()->getConfigData('general.sitemap.products.frequency'))
            ->setPriority(core()->getConfigData('general.sitemap.products.priority'));
    }
}
