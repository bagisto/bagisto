<?php

namespace Webkul\Discount\Helpers\Cart;

use Webkul\Discount\Helpers\Cart\Discount;
use Webkul\Discount\Repositories\CartRuleCartRepository as CartRuleCart;

class ValidatesDiscount extends Discount
{
    public function apply($code)
    {
        return null;
    }

    /**
     * Validates the currently applied cart rule on the current cart
     *
     * @param $cart instance
     *
     * @return mixed
     */
    public function validate()
    {
        $this->validateIfAlreadyApplied();
    }
}