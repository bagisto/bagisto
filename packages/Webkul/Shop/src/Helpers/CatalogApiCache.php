<?php

namespace Webkul\Shop\Helpers;

use Closure;
use Illuminate\Support\Facades\Cache;

class CatalogApiCache
{
    /**
     * Cache key that stores the current catalog version.
     */
    const VERSION_KEY = 'shop_api_catalog_version';

    /**
     * Lifetime (in seconds) of the catalog version counter.
     *
     * The counter only has to outlive any cached catalog response, so a long,
     * effectively-permanent TTL is used. An explicit (non-null) TTL is what
     * lets `Cache::add()` seed the counter through the store's atomic path -
     * never-expiring entries fall back to a non-atomic check-then-write.
     */
    const VERSION_TTL = 31536000;

    /**
     * Time (in seconds) a cached catalog response is kept.
     */
    const TTL = 3600;

    /**
     * Current catalog version.
     *
     * Every product/category change increments this number, which changes the
     * cache key of every catalog response and therefore serves fresh data
     * immediately without having to purge individual cache entries.
     */
    public function version(): int
    {
        return (int) Cache::get(self::VERSION_KEY, 1);
    }

    /**
     * Bump the catalog version so every cached catalog response is invalidated.
     *
     * The counter is advanced with an atomic increment - seeded by an atomic
     * `add()` on first use - so concurrent invalidations are each counted. A
     * read-then-write would let two simultaneous flushes settle on the same
     * value and lose an update.
     */
    public function flush(): void
    {
        Cache::add(self::VERSION_KEY, 1, self::VERSION_TTL);

        Cache::increment(self::VERSION_KEY);
    }

    /**
     * Catalog responses are only cached for guests. Logged-in customers receive
     * personalised data (wishlist state, customer-group prices) that must not
     * be shared across users.
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
     * Build a version-aware cache key scoped to channel, locale and currency.
     */
    protected function key(string $segment, array $params): string
    {
        return implode(':', [
            'shop_api',
            $this->version(),
            $segment,
            core()->getCurrentChannel()->id,
            core()->getCurrentLocale()->code,
            core()->getCurrentCurrencyCode(),
            md5(json_encode($params)),
        ]);
    }
}
