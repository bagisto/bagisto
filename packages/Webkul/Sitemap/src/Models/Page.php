<?php

namespace Webkul\Sitemap\Models;

use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Webkul\CMS\Models\Page as BasePage;

class Page extends BasePage implements Sitemapable
{
    /**
     * @return mixed
     */
    public function toSitemapTag(): Url|string|array
    {
        if (! $this->url_key) {
            return [];
        }

        return route('shop.cms.page', $this->url_key);
    }
}
