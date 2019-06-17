<?php

namespace Webkul\Discount\Actions;

use Webkul\Discount\Actions\Action;

class PercentOfProduct extends Action
{
    public function calculate($rule, $item, $cart)
    {
        $amountDiscounted = 0;
        $disc_threshold = $rule->disc_threshold;
        $disc_amount = $rule->disc_amount;
        $disc_quantity = $rule->disc_quantity;

        $realQty = $item['quantity'];

        if ($cart >= $disc_threshold) {
            $amountDiscounted = $item['price'] * ($disc_amount / 100);

            if ($realQty > $disc_quantity) {
                $amountDiscounted = $amountDiscounted * $disc_quantity;
            }

            if ($amountDiscounted > $item['price']) {
                $amountDiscounted = $item['price'];
            }
        }

        $report['discount'] = $amountDiscounted;
        $report['formatted_discount'] = core()->formatPrice($amountDiscounted, $cart->cart_currency_code);

        return $report;
    }
}