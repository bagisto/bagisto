<?php

namespace Webkul\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Webkul\Admin\SystemHealth\SystemHealth;

class InspectSystemHealth
{
    /**
     * Create a new middleware instance.
     */
    public function __construct(protected SystemHealth $systemHealth) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Inspect the system once a signed in admin's response has been sent, so the check never slows a
     * page and a failure in it never breaks one.
     */
    public function terminate(Request $request, Response $response): void
    {
        if (! auth()->guard('admin')->check()) {
            return;
        }

        try {
            $this->systemHealth->inspectOccasionally();
        } catch (Throwable $e) {
            report($e);
        }
    }
}
