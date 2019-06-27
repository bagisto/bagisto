<?php

namespace Webkul\Discount\Actions;

use Webkul\Discount\Actions\Action;

class BuyAGetB extends Action
{
    public function calculate($rule, $item, $cart)
    {
        //calculate discount amount
        $action_type = $rule->action_type; // action type used
        $disc_threshold = $rule->disc_threshold; // atleast quantity by default 1
        $disc_amount = $rule->disc_amount; // value of discount
        $disc_quantity = $rule->disc_quantity; //max quantity allowed to be discounted

        $amountDiscounted = 0;

        $realQty = $item['quantity'];

        if ($cart->items_qty >= $disc_threshold) {
            $amountDiscounted = $disc_amount;

            if ($realQty > $disc_quantity) {
                $amountDiscounted = $amountDiscounted * $disc_quantity;
            } else {
                $amountDiscounted = $amountDiscounted * $realQty;
            }

            if ($amountDiscounted > $item['base_price']) {
                $amountDiscounted = $item['base_price'];
            }
        }

        $report['discount'] = $amountDiscounted;
        $report['formatted_discount'] = core()->currency($amountDiscounted);

        return $report;
    }

    /**
     * Calculates the impact on the shipping amount if the rule is apply_to_shipping enabled
     */
    public function calculateOnShipping($cart)
    {
        $percentOfDiscount = ($cart->base_discount_amount * 100) / $cart->base_grand_total;

        $discountOnShipping = ($percentOfDiscount / 100) * $cart->selected_shipping_rate->base_price;

        return $discountOnShipping;
    }
}