<?php

namespace Webkul\Discount\Actions\Catalog;

use Webkul\Discount\Actions\Catalog\Action;

class FixedAmount extends Action
{
    public function calculate($rule, $product)
    {
        $discountAmount = $rule->discount_amount;
        $price = $product->price;

        if ($discountAmount <= $price) {
            return $discountAmount;
        } else {
            return $price;
        }
    }
}