<?php

namespace Webkul\Shop\Helpers;

use Closure;
use Illuminate\Support\Facades\Cache;

class CatalogApiCache
{
    /**
     * Cache key that stores the current catalog version.
     */
    public const VERSION_KEY = 'shop_api_catalog_version';

    /**
     * Lifetime (in seconds) of the catalog version counter, long and explicit so `Cache::add()`
     * can seed it atomically while it outlives every cached response.
     */
    public const VERSION_TTL = 31536000;

    /**
     * Time (in seconds) a cached catalog response is kept.
     */
    public const TTL = 3600;

    /**
     * Current catalog version, which every product or category change increments to move every
     * cached response onto a fresh key.
     */
    public function version(): int
    {
        return (int) Cache::get(self::VERSION_KEY, 1);
    }

    /**
     * Bump the catalog version so every cached catalog response is invalidated, atomically so
     * concurrent flushes are each counted.
     */
    public function flush(): void
    {
        Cache::add(self::VERSION_KEY, 1, self::VERSION_TTL);

        Cache::increment(self::VERSION_KEY);
    }

    /**
     * Whether a catalog response may be cached, which is only for guests since customers receive
     * personalised data such as wishlist state and group prices.
     */
    public function shouldCache(): bool
    {
        return ! auth()->guard('customer')->check();
    }

    /**
     * Resolve a catalog response, caching it for guests.
     */
    public function remember(string $segment, array $params, Closure $callback): mixed
    {
        if (! $this->shouldCache()) {
            return $callback();
        }

        return Cache::remember($this->key($segment, $params), self::TTL, $callback);
    }

    /**
     * Build a version-aware cache key scoped to channel, theme, locale and currency, the theme
     * because image urls follow the image templates the channel's theme registers.
     */
    protected function key(string $segment, array $params): string
    {
        return implode(':', [
            'shop_api',
            $this->version(),
            $segment,
            core()->getCurrentChannel()->id,
            core()->getCurrentChannel()->theme,
            core()->getCurrentLocale()->code,
            core()->getCurrentCurrencyCode(),
            md5(json_encode($params)),
        ]);
    }
}
