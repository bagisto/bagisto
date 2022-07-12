<?php

namespace Webkul\CartRule\Listeners;

use Webkul\CartRule\Helpers\CartRule;

class Cart
{
    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\CartRule\Repositories\CartRule  $cartRuleHepler
     * @return void
     */
    public function __construct(protected CartRule $cartRuleHepler)
    {
    }

    /**
     * Aplly valid cart rules to cart
     * 
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return void
     */
    public function applyCartRules($cart)
    {
        $this->cartRuleHepler->collect($cart);
    }
}