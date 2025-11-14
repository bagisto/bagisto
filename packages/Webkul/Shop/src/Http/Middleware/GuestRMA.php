<?php

namespace Webkul\Shop\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class GuestRMA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/');
        }

        if (empty(session()->get('guestOrderId'))) {
            return redirect()->route('shop.rma.guest.session.index');
        }

        return $next($request);
    }
}
