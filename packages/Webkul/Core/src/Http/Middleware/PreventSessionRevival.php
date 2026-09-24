<?php

namespace Webkul\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PreventSessionRevival
{
    /**
     * Cache key prefix a logged out session identifier is remembered under.
     */
    private const CACHE_PREFIX = 'revoked-session:';

    /**
     * Prefix Laravel gives every session key holding a signed in guard.
     */
    private const GUARD_PREFIX = 'login_';

    /**
     * Remember a session logged out during this request, and turn away one a late write brought back.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $session = $request->session();

        $identifier = $session->getId();

        $authenticated = $this->isAuthenticated($session);

        if (
            $authenticated
            && $this->isRevoked($identifier)
        ) {
            $session->invalidate();

            $authenticated = false;
        }

        $response = $next($request);

        if (
            $authenticated
            && ! $this->isAuthenticated($session)
        ) {
            $this->revoke($identifier);
        }

        return $response;
    }

    /**
     * Whether the session holds a guard that has been signed in.
     */
    private function isAuthenticated(Store $session): bool
    {
        foreach (array_keys($session->all()) as $key) {
            if (str_starts_with($key, self::GUARD_PREFIX)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Whether the identifier belongs to a session that has already been logged out.
     */
    private function isRevoked(string $identifier): bool
    {
        return Cache::has($this->cacheKey($identifier));
    }

    /**
     * Remember the identifier for as long as the session it named could have lived.
     */
    private function revoke(string $identifier): void
    {
        Cache::put($this->cacheKey($identifier), true, now()->addMinutes((int) config('session.lifetime')));
    }

    /**
     * The cache key an identifier is remembered under.
     */
    private function cacheKey(string $identifier): string
    {
        return self::CACHE_PREFIX.sha1($identifier);
    }
}
