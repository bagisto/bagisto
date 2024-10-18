<?php

namespace Webkul\Sitemap\Models;

use Illuminate\Support\Carbon;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Webkul\Category\Models\Category as BaseCategory;

class Category extends BaseCategory implements Sitemapable
{
    /**
     * To get the sitemap tag for the category.
     */
    public function toSitemapTag(): Url|string|array
    {
        if (
            ! $this->slug
            || ! $this->status
        ) {
            return [];
        }

        return Url::create(route('shop.product_or_category.index', $this->slug))
            ->setLastModificationDate(Carbon::create($this->updated_at))
            ->setChangeFrequency(core()->getConfigData('general.sitemap.categories.frequency'))
            ->setPriority(core()->getConfigData('general.sitemap.categories.priority'));
    }
}
