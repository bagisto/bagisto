<?php

use Webkul\Checkout\Facades\Cart;

if (! function_exists('cart')) {
    /**
     * Cart helper.
     *
     * @return \Webkul\Checkout\Cart
     */
    function cart()
    {
        return Cart::getFacadeRoot();
    }
}
