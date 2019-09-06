<?php

namespace Webkul\Discount\Actions\Cart;

use Webkul\Discount\Actions\Action;

class PercentOfProduct extends Action
{
    /**
     * To calculate impact of cart rule's action of current items of cart instance
     *
     * @param CartRule $rule
     *
     * @return boolean
     */
    public function calculate($rule)
    {
        $impact = collect();

        $totalDiscount = 0;

        $eligibleItems = $this->getEligibleItems($rule);

        $apply = function () use($rule, $eligibleItems) {
            if ($rule->action_type == 'percent_of_product') {
                return true;
            } else {
                if ($rule->action_type == 'whole_cart_to_percent' && $rule->uses_attribute_condition) {
                    $matchIDs = explode(',', $rule->product_ids);

                    foreach ($matchIDs as $matchID) {
                        foreach ($eligibleItems as $item) {
                            if ($item->child ? $item->child->product_id : $item->product_id == $matchID) {
                                return true;
                            }
                        }
                    }

                    return false;
                } else {
                    return true;
                }
            }
        };

        if ($apply()) {
            if ($rule->action_type == 'whole_cart_to_percent')
            {
                $eligibleItems = \Cart::getCart()->items;
            }

            foreach ($eligibleItems as $item) {
                $itemPrice = $item->base_price;

                $itemQuantity = $item->quantity;

                $discQuantity = $rule->disc_quantity;

                $discQuantity = $itemQuantity <= $discQuantity ? $itemQuantity : $discQuantity;

                $report = array();

                $report['item_id'] = $item->id;
                $report['product_id'] = $item->child ? $item->child->product_id : $item->product_id;

                $discount = round(($itemPrice * $rule->disc_amount) / 100, 4);

                $discount = $discount <= $itemPrice ? $discount : $itemPrice;

                $report['discount'] = $discount;

                $report['formatted_discount'] = core()->currency($discount);

                $impact->push($report);

                $totalDiscount = $totalDiscount + $discount;

                unset($report);
            }

            $impact->discount = $totalDiscount;
            $impact->formatted_discount = core()->currency($impact->discount);

            return $impact;
        }
    }
}