<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureGDPRIsEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! core()->getConfigData('general.gdpr.settings.enabled')) {
            abort(404);
        }

        return $next($request);
    }
}
