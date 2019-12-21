<?php

namespace Webkul\CartRule\Listeners;

use Webkul\CartRule\Helpers\CartRule;

/**
 * Cart event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Cart
{
    /**
     * CartRule object
     *
     * @var CartRule
     */
    protected $cartRuleHepler;

    /**
     * Create a new listener instance.
     *
     * @param  Webkul\CartRule\Repositories\CartRule $cartRuleHepler
     * @return void
     */
    public function __construct(CartRule $cartRuleHepler)
    {
        $this->cartRuleHepler = $cartRuleHepler;
    }

    /**
     * Aplly valid cart rules to cart
     * 
     * @param Cart $cart
     * @return void
     */
    public function applyCartRules($cart)
    {
        $this->cartRuleHepler->collect();
    }
}