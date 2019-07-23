<?php

namespace Webkul\Discount\Actions\Cart;

use Webkul\Discount\Actions\Action;

class WholeCartToPercent extends Action
{
    public function calculate($rule, $items, $cart)
    {
        $report = collect();

        $totalDiscount = 0;

        $disc_percent = $rule->discount_amount;

        $itemReport = collect();

        if ($disc_percent <= 100) {
            $totalDiscount = $cart->base_grand_total - $rule->discount_amount;

            $report->discount = $totalDiscount;
        } else {
            $report->discount = 0;
        }

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