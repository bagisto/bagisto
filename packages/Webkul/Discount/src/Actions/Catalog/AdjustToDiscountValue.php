<?php

namespace Webkul\Discount\Actions\Catalog;

use Webkul\Discount\Actions\Catalog\Action;

class AdjustToDiscountValue extends Action
{
    public function calculate($rule, $product)
    {
        $discountAmount = $rule->discount_amount;
        $price = $product->price;

        if ($discountAmount <= $price) {
            $discount = $price - $discountAmount;

            return $discount;
        } else {
            return $price;
        }
    }
}