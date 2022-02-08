<?php

namespace Actions;

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;

trait CartAction
{
    /**
     * Generate cart.
     *
     * @param  array  $attributes
     * @return \Webkul\Checkout\Contracts\Cart
     */
    public function haveCart($attributes = [])
    {
        return Cart::factory($attributes)->adjustCustomer()->create();
    }

    /**
     * Generate cart items.
     *
     * @param  array  $attributes
     * @return \Webkul\Checkout\Contracts\CartItem
     */
    public function haveCartItems($attributes = [])
    {
        return CartItem::factory($attributes)->adjustProduct()->create();
    }
}
