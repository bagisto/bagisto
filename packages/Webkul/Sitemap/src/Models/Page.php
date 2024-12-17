<?php

namespace Webkul\Sitemap\Models;

use Illuminate\Support\Carbon;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Tags\Url;
use Webkul\CMS\Models\Page as BasePage;

class Page extends BasePage implements Sitemapable
{
    /**
     * To get the sitemap tag for the CMS page.
     */
    public function toSitemapTag(): Url|string|array
    {
        if (! $this->url_key) {
            return [];
        }

        return Url::create(route('shop.cms.page', $this->url_key))
            ->setLastModificationDate(Carbon::create($this->updated_at))
            ->setChangeFrequency(core()->getConfigData('general.sitemap.cms.frequency'))
            ->setPriority(core()->getConfigData('general.sitemap.cms.priority'));
    }
}
