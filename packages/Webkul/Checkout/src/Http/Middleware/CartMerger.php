<?php

namespace Webkul\Checkout\Http\Middleware;

use Closure;

class CartMerger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Cart::mergeDeactivatedCart();

        return $next($request);
    }
}
