<?php

namespace Webkul\Core\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CacheGeneration
{
    /**
     * The token each repository is currently on, for the lifetime of the request.
     *
     * @var array<string, string>
     */
    protected static array $tokens = [];

    /**
     * The token a repository's cache keys are built from.
     */
    public static function get(string $repository): string
    {
        if (isset(static::$tokens[$repository])) {
            return static::$tokens[$repository];
        }

        $token = Cache::get(static::cacheKey($repository));

        if (! is_string($token)) {
            $token = static::write($repository);
        }

        return static::$tokens[$repository] = $token;
    }

    /**
     * Move a repository on to a fresh token, leaving everything cached under the old
     * one unreachable.
     */
    public static function bump(string $repository): void
    {
        static::$tokens[$repository] = static::write($repository);
    }

    /**
     * Forget the tokens held for this request, so the next read resolves them again.
     */
    public static function flush(): void
    {
        static::$tokens = [];
    }

    /**
     * Store a new token for a repository and return it.
     */
    protected static function write(string $repository): string
    {
        $token = Str::random(16);

        Cache::forever(static::cacheKey($repository), $token);

        return $token;
    }

    /**
     * The key a repository's token is held under.
     */
    protected static function cacheKey(string $repository): string
    {
        return 'repository-generation:'.$repository;
    }
}
