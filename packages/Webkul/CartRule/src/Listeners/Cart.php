<?php

namespace Webkul\CartRule\Listeners;

use Webkul\CartRule\Helpers\CartRule;

class Cart
{
    /**
     * CartRule object
     *
     * @var \Webkul\CartRule\Helpers\CartRule
     */
    protected $cartRuleHepler;

    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\CartRule\Repositories\CartRule  $cartRuleHepler
     * @return void
     */
    public function __construct(CartRule $cartRuleHepler)
    {
        $this->cartRuleHepler = $cartRuleHepler;
    }

    /**
     * Aplly valid cart rules to cart
     * 
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return void
     */
    public function applyCartRules($cart)
    {
        $this->cartRuleHepler->collect();
    }
}