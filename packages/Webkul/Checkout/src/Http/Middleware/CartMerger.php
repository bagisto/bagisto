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
        /**
         * Currently removing the buy now cart only because in live
         * instance merging not happen properly need to check this.
         */
        \Cart::activateCartIfSessionHasDeactivatedCartId();

        return $next($request);
    }
}
