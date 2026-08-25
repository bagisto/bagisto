<?php

namespace Webkul\FPC\Concerns;

use Spatie\ResponseCache\Facades\ResponseCache;

trait ForgetsPages
{
    /**
     * Drop the given storefront paths from the page cache, in every scope they were cached under.
     *
     * A cached page is keyed by its path *and* a suffix of channel, locale and currency, which
     * `ResponseCache::forget()` builds from whichever request is in flight — an admin one, when a
     * listener runs. On a store with a second locale or currency that suffix never matches the one
     * the visitor's page was stored under, so the entry survives and the storefront keeps serving
     * it. Every combination is asked for explicitly instead.
     */
    protected function forgetPages(array $paths): void
    {
        $paths = array_values(array_unique(array_filter($paths)));

        if (! $paths) {
            return;
        }

        foreach ($this->cacheScopes() as $suffix) {
            ResponseCache::selectCachedItems()
                ->usingSuffix($suffix)
                ->forUrls($paths)
                ->forget();
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
     * Every channel, locale and currency combination a page may have been cached under.
     *
     * The trailing segment is Spatie's own suffix, empty for a guest. A page cached for a signed-in
     * customer carries their id there and cannot be enumerated, so it is left to expire.
     */
    protected function cacheScopes(): array
    {
        $scopes = [];

        foreach (core()->getAllChannels() as $channel) {
            foreach ($channel->locales as $locale) {
                foreach ($channel->currencies as $currency) {
                    $scopes[] = $channel->code.'-'.$locale->code.'-'.$currency->code.'-';
                }
            }
        }

        return array_values(array_unique($scopes));
    }
}
