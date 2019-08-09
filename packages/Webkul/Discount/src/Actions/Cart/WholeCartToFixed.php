<?php

namespace Webkul\Discount\Actions\Cart;

use Webkul\Discount\Actions\Action;

class WholeCartToFixed extends Action
{
    public function calculate($rule)
    {
        $cart = \Cart::getCart();
        $items = $cart->items;

        $impact = collect();

        $impact->discount = $rule->disc_amount;
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

            foreach ($productIDs as $productID) {
                foreach ($items as $item) {
                    $itemPrice = $item->base_price;

                    if ($item->product->type == 'configurable') {
                        $itemProductId = $item->child->product_id;
                    } else {
                        $itemProductId = $item->product_id;
                    }

                    $discount = round(($itemPrice * $impact->discount) / 100, 4);

                    if ($itemProductId == $productID) {
                        $report = array();

                        $report['item_id'] = $item->id;
                        $report['product_id'] = $item->product_id;
                        $report['discount'] = $discount;
                        $report['formatted_discount'] = core()->currency($discount);

                        $impact->push($report);

                        unset($report);
                    }
                }
            }
        } else {
            foreach ($items as $item) {
                $itemPrice = $item->base_price;

                if ($item->product->type == 'configurable') {
                    $itemProductId = $item->child->product_id;
                } else {
                    $itemProductId = $item->product_id;
                }

                $discount = round(($itemPrice * $impact->discount) / 100, 4);

                $report = array();

                $report['item_id'] = $item->id;
                $report['product_id'] = $item->product_id;
                $report['discount'] = $discount;
                $report['formatted_discount'] = core()->currency($discount);

                $impact->push($report);

                unset($report);
            }
        }

        return $impact;
    }
}