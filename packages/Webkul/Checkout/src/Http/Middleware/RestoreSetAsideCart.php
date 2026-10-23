<?php

namespace Webkul\Checkout\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\Checkout\Cart as CheckoutCart;
use Webkul\Checkout\Facades\Cart;

class RestoreSetAsideCart
{
    /**
     * Hand back a basket set aside for a buy now purchase before the request reads the cart, so a
     * page that renders its own cart sees the restored one.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! session()->has(CheckoutCart::SET_ASIDE_CART_KEY)) {
            return $next($request);
        }

        Cart::restoreSetAsideCart();

        return $next($request);
    }
}
