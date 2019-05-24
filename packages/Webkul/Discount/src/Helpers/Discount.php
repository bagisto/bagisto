<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Repositories\CartRuleRepository as CartRule;

class Discount
{
    /**
     * To hold the cart rule repository instance
     */
    protected $cartRule;

    public function __construct(CartRule $cartRule)
    {
        $this->cartRule = $cartRule;
    }

    public function checkCoupon()
    {
        foreach($this->cartRule->all() as $rule) {
            return $rule->name;
        }
    }

    public function findAllRules()
    {
        $rules = $this->cartRule->all();

        $channel = core()->getCurrentChannel();
        $locales = $channel->locales;
    }
}