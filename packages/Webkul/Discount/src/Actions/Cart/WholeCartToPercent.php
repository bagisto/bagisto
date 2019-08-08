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
    public function calculate($rule)
    {
        $cart = \Cart::getCart();
        $items = $cart->items;

        $impact = collect();

        if ($rule->discount_amount >= 100) {
            $impact->discount = $cart->base_sub_total;
        } else {
            $impact->discount = ($rule->disc_amount / 100) * $cart->base_sub_total;
        }

        $impact->formatted_discount = core()->currency($impact->discount);

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
                $discountPerItem = $impact->discount / $matchCount;
            }

            foreach ($productIDs as $productID) {
                foreach ($items as $item) {
                    if ($item->product_id == $productID) {
                        $report = array();

                        $report['item_id'] = $item->id;
                        $report['product_id'] = $item->product_id;
                        $report['discount'] = round($discountPerItem, 4);
                        $report['formatted_discount'] = core()->currency(round($discountPerItem, 4));

                        $impact->push($report);

                        unset($report);
                    }
                }
            }
        } else {
            $discountPerItem = $impact->discount / $cart->items_qty;

            foreach ($items as $item) {
                $report = array();

                $report['item_id'] = $item->id;
                $report['product_id'] = $item->product_id;
                $report['discount'] = round($discountPerItem, 4);
                $report['formatted_discount'] = core()->currency(round($discountPerItem, 4));

                $impact->push($report);

                unset($report);
            }
        }

        return $impact;
    }

    /**
     * Calculates the impact on the shipping amount if the rule is apply_to_shipping enabled
     */
    public function calculateOnShipping()
    {
        $percentOfDiscount = ($cart->base_discount_amount * 100) / $cart->base_sub_total;

        $discountOnShipping = ($percentOfDiscount / 100) * $cart->selected_shipping_rate->base_price;

        return $discountOnShipping;
    }
}