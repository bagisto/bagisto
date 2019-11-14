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
     * To find the eligble items for the current rule,
     *
     * @param CartRule $rule
     * @return Collection $eligibleItems
     */
    public function getEligibleItems()
    {
        $rule = $this->rule;

        $cart = \Cart::getCart();

        $items = $cart->items()->get();

        $eligibleItems = collect();

        if ($this->rule->action_type == 'whole_cart_to_percent' || $this->rule->action_type == 'whole_cart_to_fixed_amount')
            $this->eligibleItems = $items;

        if (! $rule->uses_attribute_conditions) {
            return $items;
        } else {
            $productIDs = explode(',', $rule->product_ids);

            foreach ($items as $item) {
                foreach ($productIDs as $productID) {
                    $childrens = $item->children;

                    foreach ($childrens as $children) {
                        if ($children->product_id == $productID)
                            $eligibleItems->push($item);
                    }

                    if ($item->product_id == $productID)
                        $eligibleItems->push($item);
                }
            }

            return $eligibleItems;
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