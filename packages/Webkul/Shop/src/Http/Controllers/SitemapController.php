<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\SitemapIndex;
use Webkul\Sitemap\Repositories\SitemapRepository;

class SitemapController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected SitemapRepository $sitemapRepository) {}

    /**
     * Serve `/sitemap.xml` for the channel matching the current request host.
     *
     * The response is a sitemap index that points to every sub-sitemap file
     * generated for the current channel, so a single canonical URL always
     * exposes the channel's full sitemap set.
     */
    public function index(): Response
    {
        $channel = core()->getCurrentChannel();

        $baseUrl = $this->channelBaseUrl($channel);

        $disk = Storage::disk('public');

        $sitemapIndex = SitemapIndex::create();

        $sitemaps = $this->sitemapRepository->scopeQuery(function ($query) use ($channel) {
            return $query->whereHas('channels', fn ($sub) => $sub->where('channel_id', $channel->id));
        })->all();

        foreach ($sitemaps as $sitemap) {
            $files = $sitemap->additional['channels'][$channel->id]['sitemaps'] ?? [];

            foreach ($files as $file) {
                if ($disk->exists($file)) {
                    $sitemapIndex->add($baseUrl.'/storage/'.ltrim($file, '/'));
                }
            }
        }

        return response($sitemapIndex->render(), 200, [
            'Content-Type' => 'text/xml; charset=UTF-8',
        ]);
    }

    /**
     * Serve `/robots.txt` with a per-channel `Sitemap:` reference and content
     * usage signals for AI crawlers.
     */
    public function robots(): Response
    {
        $baseUrl = $this->channelBaseUrl(core()->getCurrentChannel());

        $lines = [
            'User-agent: *',
            'Disallow:',
            '',
            'Content-Signal: search=yes, ai-train=no, ai-input=no',
            'Sitemap: '.$baseUrl.'/sitemap.xml',
            '',
        ];

        return response(implode("\n", $lines), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    /**
     * Resolve a channel's fully-qualified base URL, falling back to the app URL.
     */
    protected function channelBaseUrl($channel): string
    {
        $baseUrl = rtrim((string) ($channel?->hostname ?: config('app.url')), '/');

        if (! preg_match('#^https?://#i', $baseUrl)) {
            $baseUrl = 'https://'.ltrim($baseUrl, '/');
        }

        return $baseUrl;
    }
}
