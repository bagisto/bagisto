<?php

namespace Webkul\Discount\Actions\Cart;

use Webkul\Discount\Actions\Action;

class WholeCartToPercent extends Action
{
    /**
     * To calculate impact of cart rule's action of current items of cart instance
     *
     * @param CartRule $rule
     * @param CartItem $items
     * @param Cart $cart
     *
     * @return boolean
     */
    public function calculate($rule, $items, $cart)
    {
        $report = collect();

        $totalDiscount = 0;

        if ($rule->discount_amount >= 100) {
            $report->discount = $cart->base_grand_total;
        } else {
            $report->discount = ($rule->disc_amount / 100) * $cart->base_grand_total;
        }

        $report->formatted_discount = core()->currency($report->discount);

        if ($rule->uses_attribute_conditions) {
            $productIDs = $rule->product_ids;

            $productIDs = explode(',', $productIDs);

            $matchCount = 0;

            foreach ($productIDs as $productID) {
                foreach ($items as $item) {
                    if ($item->product_id == $productID) {
                        $matchCount++;
                    }
                }
            }

            if ($matchCount > 0) {
                $discountPerItem = $report->discount / $matchCount;
            }

            foreach ($productIDs as $productID) {
                foreach ($items as $item) {
                    if ($item->product_id == $productID) {
                        $itemReport = array();

                        $itemReport['item_id'] = $item->id;
                        $itemReport['product_id'] = $item->product_id;
                        $itemReport['discount'] = $discountPerItem;
                        $itemReport['formatted_discount'] = core()->currency(0);

                        $report->push($itemReport);

                        unset($itemReport);
                    }
                }
            }
        } else {
            $discountPerItem = $report->discount / $cart->items_qty;

            foreach ($items as $item) {
                $itemReport = array();

                $itemReport['item_id'] = $item->id;
                $itemReport['product_id'] = $item->product_id;
                $itemReport['discount'] = $discountPerItem;
                $itemReport['formatted_discount'] = core()->currency(0);

                $report->push($itemReport);

                unset($itemReport);
            }
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