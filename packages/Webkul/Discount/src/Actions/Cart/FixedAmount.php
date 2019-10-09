<?php

namespace Webkul\Discount\Actions\Cart;

use Webkul\Discount\Actions\Action;

class FixedAmount extends Action
{
    public function calculate($rule)
    {
        $impact = collect();

        $totalDiscount = 0;

        $eligibleItems = $this->getEligibleItems($rule);

        $apply = function () use ($rule, $eligibleItems) {
            if ($rule->action_type == 'fixed_amount') {
                return true;
            } else {
                if ($rule->action_type == 'whole_cart_to_fixed' && $rule->uses_attribute_condition) {
                    $matchIDs = explode(',', $rule->product_ids);

                    foreach ($matchIDs as $matchID) {
                        foreach ($eligibleItems as $item) {
                            if (($item->child ? $item->child->product_id : $item->product_id) == $matchID) {
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
            if ($rule->action_type == 'whole_cart_to_fixed')
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

                if ($item->product->getTypeInstance()->isComposite()) {
                    $isQtyZero = true;

                    foreach ($item->children as $children) {
                        if ($children->quantity > 0) {
                            $isQtyZero = false;
                        }
                    }

                    if ($isQtyZero) {
                        $report['product_id'] = $item->children->first()->product_id;

                        $report['child_items'] = collect();
                    } else {
                        $report['product_id'] = $item->product_id;

                        $report['child_items'] = collect();

                        foreach ($item->children as $children) {
                            $children->discount = $rule->disc_amount;

                            $report['child_items']->push($children);
                        }
                    }
                } else {
                    $report['product_id'] = $item->product_id;

                    $report['child_items'] = collect();
                }

                $discount = round($rule->disc_amount, 4) * $discQuantity;

                $discount = $discount <= $itemPrice * $discQuantity ? $discount : $itemPrice * $discQuantity;

                $report['discount'] = $discount;

                $report['formatted_discount'] = core()->currency($discount);

                $impact->push($report);

                $totalDiscount = $totalDiscount + $discount;

                unset($report);
            }
        }

        $impact->discount = $totalDiscount;
        $impact->formatted_discount = core()->currency($impact->discount);

        return $impact;
    }
}