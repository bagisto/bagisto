<?php

namespace Webkul\Discount\Actions\Cart;

use Webkul\Discount\Actions\Cart\Cart\PercentOfProduct;

class WholeCartToPercent
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
        $actualInstance = new PercentOfProduct();

        $result = $actualInstance->calculate($rule);

        return $result;
    }
}