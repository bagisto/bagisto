<?php

namespace Webkul\Discount\Actions\Cart;

use Webkul\Discount\Actions\Action;

class FixedAmount extends Action
{
    public function calculate($rule, $items, $cart)
    {
        $report = collect();
        $totalDiscount = 0;

        foreach ($items as $item) {
            $amountDiscounted = 0;
            $itemReport = array();

            // calculate discount amount
            $action_type = $rule->action_type; // action type used
            $disc_threshold = $rule->disc_threshold; // atleast quantity by default 1 --> may be omitted in near future
            $disc_amount = $rule->disc_amount; // value of discount
            $disc_quantity = $rule->disc_quantity; //max quantity allowed to be discounted

            $realQty = $item['quantity'];

            if ($cart->items_qty >= $disc_threshold) {
                $amountDiscounted = $disc_amount;

                if ($realQty > $disc_quantity) {
                    $amountDiscounted = $amountDiscounted * $disc_quantity;
                } else {
                    $amountDiscounted = $amountDiscounted * $realQty;
                }

                if ($amountDiscounted > $item['base_price'] && $realQty == 1) {
                    $amountDiscounted = $item['base_price'];
                }
            }

            $totalDiscount = $totalDiscount + $amountDiscounted;

            $itemReport['item_id'] = $item->id;
            $itemReport['discount'] = $amountDiscounted;
            $itemReport['formatted_discount'] = core()->currency($amountDiscounted);

            $report->push($itemReport);
        }

        $report->discount = $totalDiscount;

        return $report;
    }

    /**
     * Calculates the impact on the shipping amount if the rule is apply_to_shipping enabled
     */
    public function calculateOnShipping($cart)
    {
        $cart = \Cart::getCart();

        $percentOfDiscount = ($cart->base_discount_amount * 100) / $cart->base_grand_total;

        return $percentOfDiscount;
    }
}