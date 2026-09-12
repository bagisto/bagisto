<?php

namespace Webkul\FPC\Concerns;

use Illuminate\Support\Str;
use Spatie\ResponseCache\Facades\ResponseCache;
use Webkul\Core\Contracts\Channel;

trait ForgetsPages
{
    /**
     * Drop the given storefront paths from the page cache, under every channel's host and in every
     * channel, locale and currency a guest could have had them cached under.
     */
    protected function forgetPages(array $paths): void
    {
        $paths = array_values(array_unique(array_filter($paths)));

        if (! $paths) {
            return;
        }

        foreach (core()->getAllChannels() as $channel) {
            $urls = $this->channelUrls($channel, $paths);

            foreach ($this->channelScopes($channel) as $suffix) {
                ResponseCache::selectCachedItems()
                    ->usingSuffix($suffix)
                    ->forUrls($urls)
                    ->forget();
            }
        }
    }

    /**
     * The home page, which carries the section carousels every catalog change can appear in.
     */
    protected function homePath(): string
    {
        return '/';
    }

    /**
     * Every locale and currency combination a guest's page on the channel may have been cached under,
     * the trailing segment being the page cache's own suffix, empty for a guest.
     *
     * @param  Channel  $channel
     */
    protected function channelScopes($channel): array
    {
        $scopes = [];

        foreach ($channel->locales as $locale) {
            foreach ($channel->currencies as $currency) {
                $scopes[] = $channel->code.'-'.$locale->code.'-'.$currency->code.'-';
            }
        }

        return array_values(array_unique($scopes));
    }

    /**
     * The addresses the paths are cached under for the channel: on the host the forget runs on, and on
     * the channel's own host, since the host is part of every cache key.
     *
     * @param  Channel  $channel
     */
    protected function channelUrls($channel, array $paths): array
    {
        $channelHost = $this->channelHost($channel);

        $urls = [];

        foreach ($paths as $path) {
            $url = url($path);

            $urls[] = $url;

            if ($channelHost) {
                $urls[] = Str::replaceFirst('//'.parse_url($url, PHP_URL_HOST), '//'.$channelHost, $url);
            }
        }

        return array_values(array_unique($urls));
    }

    /**
     * The host a channel is served on, read from its hostname with or without a scheme.
     *
     * @param  Channel  $channel
     */
    protected function channelHost($channel): ?string
    {
        if (blank($channel->hostname)) {
            return null;
        }

        $hostname = str_contains($channel->hostname, '://')
            ? $channel->hostname
            : 'http://'.$channel->hostname;

        return parse_url($hostname, PHP_URL_HOST) ?: null;
    }
}
