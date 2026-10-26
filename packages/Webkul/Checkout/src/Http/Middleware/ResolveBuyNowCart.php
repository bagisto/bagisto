<?php

namespace Webkul\Checkout\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Webkul\Checkout\Facades\Cart;

class ResolveBuyNowCart
{
    /**
     * Point the cart at the buy now cart the request names, where this visitor owns it, so the
     * checkout and the gateway act on it rather than the shopper's basket for this request alone.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($token = $request->input('buy_now')) {
            if ($cartId = Cart::parseBuyNowToken($token)) {
                Cart::useBuyNowCart($cartId);
            }
        }

        return $next($request);
    }
}
