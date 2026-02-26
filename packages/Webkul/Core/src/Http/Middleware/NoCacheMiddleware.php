<?php

namespace Webkul\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoCacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');

        $response->headers->set('Pragma', 'no-cache');

        $response->headers->set('Expires', '0');

        return $response;
    }
}
