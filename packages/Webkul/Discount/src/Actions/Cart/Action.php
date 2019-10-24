<?php

namespace Webkul\Discount\Actions\Cart;

abstract class Action
{
    /**
     * To hold the current rule
     */
    protected $rule;

    abstract public function calculate($rule);

    /**
     * Empty collection instance for keeping final list of items
     */
    protected $matchedItems;

    public function __construct()
    {
        /**
         * Making $matchedItems property empty collection instance.
         */
        $this->matchedItems = collect();
    }

    /**
     * To find the eligble items for the current rule,
     *
     * @param CartRule $rule
     *
     * @return Collection $matchedItems
     */
    public function getEligibleItems($rule)
    {
        $cart = \Cart::getCart();

        $items = $cart->items()->get();

        $productIDs = $rule->product_ids;

        $productIDs = explode(',', $productIDs);

        $matchCriteria = $rule->uses_attribute_conditions ? $rule->product_ids : '*';

        if ($matchCriteria == '*') {
            $this->matchedItems = $items;

            return $this->matchedItems;
        } else {
            $matchingIDs = explode(',', $matchCriteria);

            foreach ($items as $item) {
                foreach ($matchingIDs as $matchingID) {
                    $childrens = collect();
                    $childrens = $item->children;

                    foreach ($childrens as $children) {
                        if ($children->product_id == $matchingID) {
                            $this->pushItem($children);
                        }
                    }

                    if ($item->product_id == $matchingID) {
                        $this->pushItem($item);
                    }
                }
            }

            return $this->matchedItems;
        }
    }

    /**
     * To check the items applicability
     */
    public function checkApplicability()
    {
        $rule = $this->rule;

        $eligibleItems = $this->getEligibleItems($rule);

        $apply = function () use($rule, $eligibleItems) {
            if ($rule->action_type == 'percent_of_product') {
                return true;
            } else {
                if ($rule->action_type == 'whole_cart_to_percent' && $rule->uses_attribute_condition) {
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

        return $apply();
    }


    private function pushItem($item) {
        $this->matchedItems->push($item);
    }
}