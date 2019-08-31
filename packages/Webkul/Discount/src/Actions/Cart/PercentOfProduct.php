<?php

namespace Webkul\Discount\Actions\Cart;

use Webkul\Discount\Actions\Action;

class PercentOfProduct extends Action
{
    public function calculate($rule)
    {
        $cart = \Cart::getCart();
        $items = $cart->items;

        $impact = collect();

        $totalDiscount = 0;

        if ($rule->discount_amount >= 100) {
            $impact->discount = $cart->base_sub_total;

            $impact->formatted_discount = core()->currency($impact->discount);
        }

        if ($rule->uses_attribute_conditions) {
            $productIDs = $rule->product_ids;

            $productIDs = explode(',', $productIDs);

            // $matchCount = 0;

            // foreach ($productIDs as $productID) {
            //     foreach ($items as $item) {
            //         if ($item->product_id == $productID) {
            //             $matchCount++;
            //         }
            //     }
            // }

            foreach ($productIDs as $productID) {
                foreach ($items as $item) {
                    $itemPrice = $item->base_price;

                    if ($item->product->type == 'configurable') {
                        $itemProductId = $item->child->product_id;
                    } else {
                        $itemProductId = $item->product_id;
                    }

                    $itemQuantity = $item->quantity;

                    $discQuantity = $rule->disc_quantity;

                    if ($discQuantity > 1) {
                        if ($itemQuantity >= $discQuantity) {
                            $discount = round(($itemPrice * $rule->disc_amount) / 100, 4) * $discQuantity;

                            if ($discount >= $itemPrice) {
                                $discount = $itemPrice;
                            }
                        } else if ($itemQuantity < $discQuantity) {
                            $discount = round(($itemPrice * $rule->disc_amount) / 100, 4) * $itemQuantity;

                            if ($discount >= $itemPrice) {
                                $discount = $itemPrice;
                            }
                        }
                    } else {
                        $discount = round(($itemPrice * $rule->disc_amount) / 100, 4);

                        if ($discount >= $itemPrice) {
                            $discount = $itemPrice;
                        }
                    }

                    if ($itemProductId == $productID) {
                        $totalDiscount = $totalDiscount + $discount;

                        $report = array();

                        $report['item_id'] = $item->id;
                        $report['product_id'] = $item->child ? $item->child->product_id : $item->product_id;
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

                $itemQuantity = $item->quantity;

                $discQuantity = $rule->disc_quantity;

                if ($discQuantity > 1) {
                    if ($itemQuantity >= $discQuantity) {
                        $discount = round(($itemPrice * $rule->disc_amount) / 100, 4) * $discQuantity;

                        if ($discount >= $itemPrice) {
                            $discount = $itemPrice;
                        }
                    } else if ($itemQuantity < $discQuantity) {
                        $discount = round(($itemPrice * $rule->disc_amount) / 100, 4) * $itemQuantity;

                        if ($discount >= $itemPrice) {
                            $discount = $itemPrice;
                        }
                    }
                } else {
                    $discount = round(($itemPrice * $rule->disc_amount) / 100, 4);

                    if ($discount >= $itemPrice) {
                        $discount = $itemPrice;
                    }
                }

                $totalDiscount = $totalDiscount + $discount;

                $report = array();

                $report['item_id'] = $item->id;
                $report['product_id'] = $item->child ? $item->child->product_id : $item->product_id;

                if ($discount <= $itemPrice) {
                    $report['discount'] = $discount;
                } else {
                    $report['discount'] = $itemPrice;
                }

                $report['formatted_discount'] = core()->currency($discount);

                $impact->push($report);

                unset($report);
            }
        }


        $impact->discount = $totalDiscount;
        $impact->formatted_discount = core()->currency($impact->discount);

        return $impact;
    }
}