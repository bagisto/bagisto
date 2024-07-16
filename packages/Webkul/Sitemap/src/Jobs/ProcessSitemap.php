<?php

namespace Webkul\Sitemap\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Webkul\Sitemap\Contracts\Sitemap as SitemapContract;
use Webkul\Sitemap\Models\Category;
use Webkul\Sitemap\Models\Page;
use Webkul\Sitemap\Models\Product;

class ProcessSitemap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public SitemapContract $sitemap
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // configurations that needs to be use...
        // core()->getConfigData('general.sitemap.settings.enabled');
        // core()->getConfigData('general.sitemap.file_limits.max_url_per_file');
        // core()->getConfigData('general.sitemap.file_limits.max_file_size');
        // core()->getConfigData('general.sitemap.store_url.frequency');
        // core()->getConfigData('general.sitemap.store_url.priority');
        // core()->getConfigData('general.sitemap.categories.frequency');
        // core()->getConfigData('general.sitemap.categories.priority');
        // core()->getConfigData('general.sitemap.products.frequency');
        // core()->getConfigData('general.sitemap.products.priority');
        // core()->getConfigData('general.sitemap.cms.frequency');
        // core()->getConfigData('general.sitemap.cms.priority');

        $sitemapFilePath = $this->sitemap->path.'/'.$this->sitemap->file_name;

        if (Storage::exists($sitemapFilePath)) {
            Storage::delete($sitemapFilePath);
        }

        Sitemap::create()
            ->add(Url::create('/'))
            ->add(Category::all())
            ->add(Product::all())
            ->add(Page::all())
            ->writeToDisk('public', $sitemapFilePath);
    }
}
