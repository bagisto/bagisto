<?php

namespace Webkul\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Symfony\Component\HttpFoundation\Response;
use Webkul\Core\Helpers\RevokedSessions;

class PreventSessionRevival
{
    /**
     * Prefix Laravel gives every session key holding a signed in guard.
     */
    private const GUARD_PREFIX = 'login_';

    /**
     * Create a new middleware instance.
     */
    public function __construct(protected RevokedSessions $revokedSessions) {}

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
            && $this->revokedSessions->has($identifier)
        ) {
            $session->invalidate();

            $authenticated = false;
        }

        $response = $next($request);

        if (
            $authenticated
            && ! $this->isAuthenticated($session)
        ) {
            $this->revokedSessions->revoke($identifier);
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
}
