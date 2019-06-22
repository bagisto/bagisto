<?php

namespace Webkul\SAASCustomizer\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotSuperAdmin
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @param  string|null  $guard
    * @return mixed
    */
    public function handle($request, Closure $next, $guard = 'super-admin')
    {
        if (! Auth::guard($guard)->check()) {
            return redirect()->route('customer.session.index');
        }

        return $next($request);
    }
}
