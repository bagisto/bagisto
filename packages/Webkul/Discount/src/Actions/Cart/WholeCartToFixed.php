<?php

namespace Webkul\Discount\Actions\Cart;

use Webkul\Discount\Actions\Action;

class WholeCartToFixed extends Action
{
    public function calculate($rule, $items, $cart)
    {
        $report = collect();

        $totalDiscount = 0;

        foreach ($items as $item) {
            $itemReport = array();

            $itemReport['item_id'] = $item->id;
            $itemReport['product_id'] = $item->product_id;
            $itemReport['discount'] = 0;
            $itemReport['formatted_discount'] = core()->currency(0);

            $report->push($itemReport);

            unset($itemReport);
        }

        $report->discount = $cart->base_grand_total - $rule->discount_amount;
        $report->formatted_discount = core()->currency($report->discount);

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