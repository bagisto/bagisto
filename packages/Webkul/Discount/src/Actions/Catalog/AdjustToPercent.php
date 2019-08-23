<?php

namespace Webkul\Discount\Actions\Catalog;

use Webkul\Discount\Actions\Catalog\Action;

class AdjustToPerenct extends Action
{
    public function calculate($rule, $product)
    {
        $discountAmount = $rule->discount_amount;

        $price = $product->price;

        $discount = ($discountAmount / 100) * $price;

        return $discount;
    }
}