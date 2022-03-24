<?php

if (! function_exists('cart')) {
    /**
     * Cart helper.
     *
     * @return \Webkul\Checkout\Cart
     */
    function cart()
    {
        return app()->make('cart');
    }
}
