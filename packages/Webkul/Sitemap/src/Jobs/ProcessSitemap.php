<?php

namespace Webkul\Sitemap\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
use Webkul\Sitemap\Contracts\Sitemap as SitemapContract;
use Webkul\Sitemap\Models\Category;
use Webkul\Sitemap\Models\Page;
use Webkul\Sitemap\Models\Product;

class ProcessSitemap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Batch processed.
     */
    protected int $batchProcessed = 0;

    /**
     * Items to be processed.
     */
    protected array $itemsToBeProcessed = [];

    /**
     * Generated sitemap index.
     */
    protected string $generatedSitemapIndex = '';

    /**
     * Generated sitemaps.
     */
    protected array $generatedSitemaps = [];

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
        /**
         * If sitemap is disabled then return.
         */
        if (! core()->getConfigData('general.sitemap.settings.enabled')) {
            return;
        }

        /**
         * If the sitemap is already generated then delete the existing sitemap.
         */
        $this->sitemap->deleteFromStorage();

        /**
         * Process the store URL.
         */
        $this->processItems([
            Url::create('/')
                ->setChangeFrequency(core()->getConfigData('general.sitemap.store_url.frequency') ?: $this->sitemap::DEFAULT_FREQUENCY)
                ->setPriority(core()->getConfigData('general.sitemap.store_url.priority') ?: $this->sitemap::DEFAULT_PRIORITY),
        ]);

        /**
         * Process the categories.
         */
        Category::query()->chunk(100, fn ($items) => $this->processItems($items));

        /**
         * Process the products.
         */
        Product::query()->chunk(100, fn ($items) => $this->processItems($items));

        /**
         * Process the CMS pages.
         */
        Page::query()->chunk(100, fn ($items) => $this->processItems($items));

        /**
         * If there are any items left to be processed then generate the sitemap.
         */
        if (! empty($this->itemsToBeProcessed)) {
            $this->batchProcessed++;

            $this->generateSitemap();

            $this->itemsToBeProcessed = [];
        }

        /**
         * After generating all the sitemaps, we will generate the index.
         */
        $this->generateSitemapIndex();

        /**
         * Update the sitemap with the generated sitemap index and sitemaps.
         */
        $this->sitemap->update([
            'generated_at' => now(),
            'additional'   => array_merge($this->sitemap->additional ?? [], [
                'index'    => $this->generatedSitemapIndex,
                'sitemaps' => $this->generatedSitemaps,
            ]),
        ]);
    }

    /**
     * Process items.
     *
     * @param  mixed  $items
     */
    protected function processItems($items): void
    {
        foreach ($items as $item) {
            $this->itemsToBeProcessed[] = $item;

            if (count($this->itemsToBeProcessed) === ((int) core()->getConfigData('general.sitemap.file_limits.max_url_per_file') ?: $this->sitemap::DEFAULT_MAX_URLS)) {
                $this->batchProcessed++;

                $this->generateSitemap();

                $this->itemsToBeProcessed = [];
            }
        }
    }

    /**
     * Generate sitemap.
     */
    protected function generateSitemap(): void
    {
        $sitemap = Sitemap::create();

        foreach ($this->itemsToBeProcessed as $item) {
            $sitemap->add($item);
        }

        $fileName = $this->sitemap->path.'/'.File::name($this->sitemap->file_name).'-'.$this->sitemap->id.'-'.$this->batchProcessed.'.'.File::extension($this->sitemap->file_name);

        $sitemap->writeToDisk('public', $fileName);

        $this->generatedSitemaps[] = collect(explode('/', $fileName))->filter(fn ($segment) => ! empty($segment))->join('/');
    }

    /**
     * Generate sitemap index.
     */
    protected function generateSitemapIndex(): void
    {
        $sitemap = SitemapIndex::create();

        foreach ($this->generatedSitemaps as $generatedSitemap) {
            $sitemap->add(Storage::url($generatedSitemap));
        }

        $sitemapFilePath = $this->sitemap->path.'/'.$this->sitemap->file_name;

        $sitemap->writeToDisk('public', $sitemapFilePath);

        $this->generatedSitemapIndex = collect(explode('/', $sitemapFilePath))->filter(fn ($segment) => ! empty($segment))->join('/');
    }
}
