<?php

namespace Webkul\Sitemap\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL as URLFacade;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;
use Webkul\Core\Contracts\Channel;
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

        if ($this->sitemap->channels->isEmpty()) {
            return;
        }

        $originalRoot = url('/');

        $channelResults = [];

        try {
            foreach ($this->sitemap->channels as $channel) {
                $channelResults[$channel->id] = $this->processChannel($channel);
            }
        } finally {
            URLFacade::forceRootUrl($originalRoot);
        }

        $this->sitemap->update([
            'generated_at' => now(),

            'additional' => array_merge($this->sitemap->additional ?? [], [
                'channels' => $channelResults,
            ]),
        ]);
    }

    /**
     * Process a single channel and return its generated file references.
     *
     * @param  Channel  $channel
     * @return array
     */
    protected function processChannel($channel)
    {
        $this->batchProcessed = 0;
        $this->itemsToBeProcessed = [];
        $this->generatedSitemaps = [];

        $baseUrl = $this->channelBaseUrl($channel);

        URLFacade::forceRootUrl($baseUrl);

        $this->processItems($channel, [Url::create($baseUrl.'/')]);

        $this->processCategories($channel);

        Product::query()
            ->whereHas('channels', fn ($query) => $query->where('channel_id', $channel->id))
            ->chunk(100, fn ($items) => $this->processItems($channel, $items));

        Page::query()
            ->whereHas('channels', fn ($query) => $query->where('channel_id', $channel->id))
            ->chunk(100, fn ($items) => $this->processItems($channel, $items));

        if (! empty($this->itemsToBeProcessed)) {
            $this->generateSitemap($channel);
        }

        return [
            'hostname' => $baseUrl,
            'index' => $this->generateSitemapIndex($channel, $baseUrl),
            'sitemaps' => $this->generatedSitemaps,
        ];
    }

    /**
     * Process categories under the channel's root category subtree.
     *
     * @param  Channel  $channel
     * @return void
     */
    protected function processCategories($channel)
    {
        $root = Category::query()->find($channel->root_category_id, ['_lft', '_rgt']);

        if (! $root) {
            return;
        }

        Category::query()
            ->whereBetween('_lft', [$root->_lft + 1, $root->_rgt - 1])
            ->chunk(100, fn ($items) => $this->processItems($channel, $items));
    }

    /**
     * Buffer items and flush when the per-file URL cap is reached.
     *
     * @param  Channel  $channel
     * @param  mixed  $items
     * @return void
     */
    protected function processItems($channel, $items)
    {
        foreach ($items as $item) {
            $this->itemsToBeProcessed[] = $item;

            if (count($this->itemsToBeProcessed) === (int) core()->getConfigData('general.sitemap.file_limits.max_url_per_file')) {
                $this->generateSitemap($channel);
            }
        }
    }

    /**
     * Generate a sub-sitemap file for the given channel.
     *
     * @param  Channel  $channel
     * @return void
     */
    protected function generateSitemap($channel)
    {
        $this->batchProcessed++;

        $sitemap = Sitemap::create();

        foreach ($this->itemsToBeProcessed as $item) {
            $sitemap->add($item);
        }

        $path = $this->buildFilePath($channel, $this->batchProcessed);

        $sitemap->writeToDisk('public', $path);

        $this->generatedSitemaps[] = $path;

        $this->itemsToBeProcessed = [];
    }

    /**
     * Generate the sitemap index for the given channel.
     *
     * @param  Channel  $channel
     * @param  string  $baseUrl
     * @return string
     */
    protected function generateSitemapIndex($channel, $baseUrl)
    {
        $sitemap = SitemapIndex::create();

        foreach ($this->generatedSitemaps as $generatedSitemap) {
            $sitemap->add($baseUrl.'/storage/'.ltrim($generatedSitemap, '/'));
        }

        $path = $this->buildFilePath($channel);

        $sitemap->writeToDisk('public', $path);

        return $path;
    }

    /**
     * Build the sub-sitemap or index file path for the given channel.
     *
     * Every generated file is rooted under the fixed prefix `sitemaps/{channel_code}/`
     * so channel isolation on disk is guaranteed regardless of what the user enters
     * in the sitemap path field.
     *
     * @param  Channel  $channel
     * @param  int|null  $batch
     * @return string
     */
    protected function buildFilePath($channel, $batch = null)
    {
        $suffix = $batch === null ? '' : '-'.$batch;

        return clean_path(
            'sitemaps/'.$channel->code
            .'/'.$this->sitemap->path
            .'/'.File::name($this->sitemap->file_name)
            .'-'.$this->sitemap->id
            .'-'.$channel->id
            .$suffix
            .'.'.File::extension($this->sitemap->file_name)
        );
    }

    /**
     * Normalize a channel hostname into a fully-qualified base URL.
     *
     * @param  Channel  $channel
     * @return string
     */
    protected function channelBaseUrl($channel)
    {
        $hostname = rtrim(trim((string) $channel->hostname), '/');

        if ($hostname === '') {
            return rtrim(config('app.url'), '/');
        }

        if (! preg_match('#^https?://#i', $hostname)) {
            $hostname = 'https://'.$hostname;
        }

        return $hostname;
    }
}
