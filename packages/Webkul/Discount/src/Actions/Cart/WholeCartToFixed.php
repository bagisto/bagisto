<?php

namespace Webkul\Discount\Actions\Cart;

use Webkul\Discount\Actions\Cart\Cart\FixedAmount;

class WholeCartToFixed
{
    /**
     * To calculate impact of cart rule's action of current items of cart instance
     *
     * @param CartRule $rule
     *
     * @return boolean
     */
    public function calculate($rule)
    {
        $actualInstance = new FixedAmount();

        $result = $actualInstance->calculate($rule);

        return $result;
    }
}