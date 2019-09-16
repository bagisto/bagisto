<?php

namespace Webkul\Discount\Actions;

abstract class Action
{
    abstract public function calculate($rule);

    public function getEligibleItems($rule)
    {
        $cart = \Cart::getCart();
        $items = $cart->items;

        $matchedItems = collect();

        $productIDs = $rule->product_ids;

        $productIDs = explode(',', $productIDs);

        $matchCriteria = $rule->uses_attribute_conditions ? $rule->product_ids : '*';

        if ($matchCriteria == '*') {
            return $items;
        } else {
            $matchingIDs = explode(',', $matchCriteria);

            foreach ($items as $item) {
                foreach ($matchingIDs as $matchingID) {
                    if (isset($item->children)) {
                        $isQtyZero = true;

                        foreach ($item->children as $children) {
                            if ($children->quantity > 0) {
                                $isQtyZero = false;
                            }
                        }

                        if ($isQtyZero) {
                            if ($matchingID == ($item->child ? $item->child->product_id : $item->product_id)) {
                                $matchedItems->push($item);
                            }
                        } else {
                            if ($matchingID == $item->product_id) {
                                $matchedItems->push($item);
                            }
                        }
                    } else {
                        if ($matchingID == $item->product_id) {
                            $matchedItems->push($item);
                        }
                    }
                }
            }

            return $matchedItems;
        }
    }
}