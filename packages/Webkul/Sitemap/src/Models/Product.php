<?php

namespace Webkul\Sitemap\Models;

use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Webkul\Product\Models\Product as BaseProduct;

class Product extends BaseProduct implements Sitemapable
{
    /**
     * @return mixed
     */
    public function toSitemapTag(): Url | string | array
    {
        if (
            ! $this->url_key
            || ! $this->status
            || ! $this->visible_individually
        ) {
            return [];
        }

        return route('shop.productOrCategory.index', $this->url_key);
    }
}