<?php

namespace Webkul\Checkout\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\Checkout\Cart as CheckoutCart;
use Webkul\Checkout\Facades\Cart;

class RestoreParkedCart
{
    /**
     * Hand back a basket parked for a buy now purchase before the request reads the cart, so a
     * page that renders its own cart sees the restored one.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! session()->has(CheckoutCart::PARKED_CART_KEY)) {
            return $next($request);
        }

        Cart::restoreParkedCart();

        return $next($request);
    }
}
