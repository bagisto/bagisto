<?php

namespace Webkul\Discount\Actions\Cart;

use Webkul\Discount\Actions\Cart\Action;

class FixedAmount extends Action
{
    public function calculate($rule)
    {
        /**
         * Setting the rule getting applied
         */
        $this->rule = $rule;

        $impact = collect();

        $totalDiscount = 0;

        $applicability = $this->checkApplicability();

        if ($applicability) {
            // if ($rule->action_type == 'whole_cart_to_fixed') {
            //     $eligibleItems = \Cart::getCart()->items;
            // }

            $eligibleItems = \Cart::getCart()->items;

            foreach ($eligibleItems as $item) {
                $report = array();

                $report['item_id'] = $item->id;
                $report['child_items'] = collect();

                $itemPrice = $item->base_price;

                $itemQuantity = $item->quantity;

                $discQuantity = $rule->disc_quantity;

                $discQuantity = $itemQuantity <= $discQuantity ? $itemQuantity : $discQuantity;

                if ($item->product->getTypeInstance()->isComposite()) {
                    $isQtyZero = true;

                    foreach ($item->children as $children) {
                        if ($children->quantity > 0) {
                            $isQtyZero = false;
                        }
                    }

                    if ($isQtyZero) {
                        // case for configurable products
                        $report['product_id'] = $item->children->first()->product_id;
                    } else {
                        // composites other than configurable
                        $report['product_id'] = $item->product_id;

                        foreach ($item->children as $children) {
                            $childBaseTotal = $children->base_total;

                            $itemDiscount = $childBaseTotal / (\Cart::getCart()->base_sub_total / 100);

                            $children->discount = ($itemDiscount / 100) * $rule->disc_amount;

                            $children->discount = $children->base_total > $children->discount ? $children->discount : $children->base_total;

                            $report['child_items']->push($children);
                        }
                    }
                } else {
                    $report['product_id'] = $item->product_id;
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