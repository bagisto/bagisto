<?php

namespace Webkul\Discount\Actions\Cart;

abstract class Action
{
    /**
     * To hold the current rule
     */
    protected $rule;

    /**
     * Empty collection instance for keeping final list of items
     */
    protected $matchedItems;

    public function __construct()
    {
        $this->matchedItems = collect();
    }

    abstract public function calculate($rule);

    /**
     * To find the eligble items for the current rule,
     *
     * @param CartRule $rule
     * @return Collection $matchedItems
     */
    public function getEligibleItems()
    {
        $rule = $this->rule;

        $cart = \Cart::getCart();

        $items = $cart->items()->get();

        if ($this->rule->action_type == 'whole_cart_to_percent' || $this->rule->action_type == 'whole_cart_to_fixed_amount')
            $this->matchedItems = $items;

        if (! $rule->uses_attribute_conditions) {
            $this->matchedItems = $items;

            return $this->matchedItems;
        } else {
            $productIDs = explode(',', $rule->product_ids);

            foreach ($items as $item) {
                foreach ($productIDs as $productID) {
                    $childrens = $item->children;

                    foreach ($childrens as $children) {
                        if ($children->product_id == $productID)
                            $this->matchedItems->push($children);
                    }

                    if ($item->product_id == $productID)
                        $this->matchedItems->push($item);
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
                    $matchingIds = explode(',', $rule->product_ids);

                    foreach ($matchingIds as $matchingId) {
                        foreach ($eligibleItems as $item) {
                            if (($item->child ? $item->child->product_id : $item->product_id) == $matchingId)
                                return true;
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
}