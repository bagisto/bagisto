<?php

namespace Webkul\Discount\Actions;

class BuyAGetB
{
    public function __construct()
    {
    }

    public function calculate($rule, $item, $cart)
    {
        //calculate discount amount
        $action_type = $rule->action_type; // action type used
        $disc_threshold = $rule->disc_threshold; // atleast quantity by default 1 --> may be omitted in near future
        $disc_amount = $rule->disc_amount; // value of discount
        $disc_quantity = $rule->disc_quantity; //max quantity allowed to be discounted

        $amountDiscounted = 0;
        $leastWorthItem = $this->leastWorthItem();
        $realQty = $leastWorthItem['quantity'];

        if ($cart->items_qty >= $disc_threshold) {
            $amountDiscounted = $disc_amount;

            if ($realQty > $disc_quantity) {
                $amountDiscounted = $amountDiscounted * $disc_quantity;
            }

            if ($amountDiscounted > $leastWorthItem['price']) {
                $amountDiscounted = $leastWorthItem['price'];
            }
        }

        $report['discount'] = $amountDiscounted;
        $report['formatted_discount'] = core()->formatPrice($amountDiscounted, $cart->cart_currency_code);

        return $report;
    }
}