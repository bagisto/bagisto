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
    protected $cartRuleHelper;

    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\CartRule\Repositories\CartRule  $cartRuleHelper
     * @return void
     */
    public function __construct(CartRule $cartRuleHelper)
    {
        $this->cartRuleHelper = $cartRuleHelper;
    }

    /**
     * Aplly valid cart rules to cart
     *
     * @param  \Webkul\Checkout\Contracts\Cart  $cart
     * @return void
     */
    public function applyCartRules($cart)
    {
        $this->cartRuleHelper->collect();
    }
}