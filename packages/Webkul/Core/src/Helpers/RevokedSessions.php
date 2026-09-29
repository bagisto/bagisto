<?php

namespace Webkul\Core\Helpers;

use Illuminate\Support\Facades\Cache;

class RevokedSessions
{
    /**
     * Cache key prefix a revoked session identifier is remembered under.
     */
    private const CACHE_PREFIX = 'revoked-session:';

    /**
     * Remember an identifier for as long as the session it named could have lived.
     */
    public function revoke(string $identifier): void
    {
        Cache::put($this->cacheKey($identifier), true, now()->addMinutes((int) config('session.lifetime')));
    }

    /**
     * Whether the identifier names a session that has already been taken away.
     */
    public function has(string $identifier): bool
    {
        return Cache::has($this->cacheKey($identifier));
    }

    /**
     * The cache key an identifier is remembered under.
     */
    private function cacheKey(string $identifier): string
    {
        return self::CACHE_PREFIX.sha1($identifier);
    }
}
