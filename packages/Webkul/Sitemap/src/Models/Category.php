<?php

namespace Webkul\Sitemap\Models;

use Illuminate\Support\Carbon;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Webkul\Category\Models\Category as BaseCategory;

class Category extends BaseCategory implements Sitemapable
{
    protected function rootCategoryIds()
    {
        return cache()->remember('root_category_ids', 300, function () {
            return core()->getAllChannels()->pluck('root_category_id')->toArray();
        });
    }

    /**
     * To get the sitemap tag for the category.
     */
    public function toSitemapTag(): Url|string|array
    {
        if (
            ! $this->slug
            || ! $this->status
            || in_array($this->id, $this->rootCategoryIds())
        ) {
            return [];
        }

        return Url::create(route('shop.product_or_category.index', $this->slug))
            ->setLastModificationDate(Carbon::create($this->updated_at));
    }
}
