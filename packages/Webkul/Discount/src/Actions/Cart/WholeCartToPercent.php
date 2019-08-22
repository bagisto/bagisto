<?php

namespace Webkul\Discount\Actions\Cart;

use Webkul\Discount\Actions\Action;
use Cart;

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

        $totalDiscount = 0;

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
                foreach ($productIDs as $productID) {
                    foreach ($items as $item) {
                        $itemPrice = $item->base_price;

                        $discount = round(($itemPrice * $rule->disc_amount) / 100, 4);

                        $totalDiscount = $totalDiscount + $discount;

                        if ($item->product_id == $productID) {
                            $report = array();

                            $report['item_id'] = $item->id;
                            $report['product_id'] = $item->child ? $item->child->product_id : $item->product_id;
                            $report['discount'] = $discount;
                            $report['formatted_discount'] = core()->currency(round($discount, 4));

                            $impact->push($report);

                            unset($report);
                        }
                    }
                }
            }
        } else {
            foreach ($items as $item) {
                $itemPrice = $item->base_price;

                $discount = round(($itemPrice * $rule->disc_amount) / 100, 4);

                $totalDiscount = $totalDiscount + $discount;

                if ($item->product_id == $productID) {
                    $report = array();

                    $report['item_id'] = $item->id;
                    $report['product_id'] = $item->child ? $item->child->product_id : $item->product_id;
                    $report['discount'] = $discount;
                    $report['formatted_discount'] = core()->currency(round($discount, 4));

                    $impact->push($report);

                    unset($report);
                }
            }
        }

        $impact->discount = $totalDiscount;

        $impact->fomatted_discount = core()->currency($impact->discount);

        return $impact;
    }
}