<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Repositories\CartRuleCartRepository as CartRuleCart;

class ValidatesDiscount
{
    /**
     * CartRuleCartRepository instance
     */
    protected $cartRuleCart;

    /**
     * Initializes type hinted dependencies
     *
     * @param CartRuleCart $cartRuleCart
     */
    public function __construct(CartRuleCart $cartRuleCart)
    {
        $this->cartRuleCart = $cartRuleCart;
    }

    /**
     * Validates the currently applied cart rule on the current cart
     *
     * @param $cart instance
     *
     * @return mixed
     */
    public function validate($cart)
    {
        $appliedRule = $this->cartRuleCart->findWhere([
            'cart_id' => $cart->id
        ]);

        if ($appliedRule->count()) {
            $appliedRule = $appliedRule->first()->cart_rule;

            if ($appliedRule->status == 1) {
                $applicability = $this->checkApplicability($appliedRule);

                if (! $applicability) {
                    return $this->remove();
                } else {
                    if ($appliedRule->free_shipping && $cart->selected_shipping_rate->base_price > 0) {
                        $cart->selected_shipping_rate->update([
                            'price' => 0,
                            'base_price' => 0
                        ]);
                    } else if ($appliedRule->free_shipping == 0 && $appliedRule->apply_to_shipping && $cart->selected_shipping_rate->base_price > 0) {
                        $actionType = config('discount-rules')[$appliedRule->action_type];

                        if ($appliedRule->apply_to_shipping) {
                            $actionInstance = new $actionType;

                            $discountOnShipping = $actionInstance->calculateOnShipping($cart);

                            $cart->selected_shipping_rate->update([
                                'price' => $cart->selected_shipping_rate->base_price - $discountOnShipping,
                                'base_price' => $cart->selected_shipping_rate->price - core()->convertPrice($discountOnShipping, $cart->cart_currency_code)
                            ]);
                        }
                    }
                }
            } else {
                return $this->remove();
            }
        }

        return false;
    }

    /**
     * Checks whether coupon is getting applied on current cart instance or not
     *
     * @return boolean
     */
    public function checkApplicability($rule)
    {
        $cart = \Cart::getCart();

        $timeBased = false;

        // time based constraints
        if ($rule->starts_from != null && $rule->ends_till == null) {
            if (Carbon::parse($rule->starts_from) < now()) {
                $timeBased = true;
            }
        } else if ($rule->starts_from == null && $rule->ends_till != null) {
            if (Carbon::parse($rule->ends_till) > now()) {
                $timeBased = true;
            }
        } else if ($rule->starts_from != null && $rule->ends_till != null) {
            if (Carbon::parse($rule->starts_from) < now() && now() < Carbon::parse($rule->ends_till)) {
                $timeBased = true;
            }
        } else {
            $timeBased = true;
        }

        $channelBased = false;

        // channel based constraints
        foreach ($rule->channels as $channel) {
            if ($channel->channel_id == core()->getCurrentChannel()->id) {
                $channelBased = true;
            }
        }

        $customerGroupBased = false;

        // customer groups based constraints
        if (auth()->guard('customer')->check()) {
            foreach ($rule->customer_groups as $customer_group) {
                if (auth()->guard('customer')->user()->group->exists()) {
                    if ($customer_group->customer_group_id == auth()->guard('customer')->user()->group->id) {
                        $customerGroupBased = true;
                    }
                }
            }
        } else {
            foreach ($rule->customer_groups as $customer_group) {
                if ($customer_group->customer_group->code == 'guest') {
                    $customerGroupBased = true;
                }
            }
        }

        $conditionsBased = true;

        //check conditions
        if ($rule->conditions != null) {
            $conditions = json_decode(json_decode($rule->conditions));

            $test_mode = array_last($conditions);

            if ($test_mode->criteria == 'any_is_true') {
                $conditionsBased = $this->testIfAnyConditionIsTrue($conditions, $cart);
            }

            if ($test_mode->criteria == 'all_are_true') {
                $conditionsBased = $this->testIfAllConditionAreTrue($conditions, $cart);
            }
        }

        if ($timeBased && $channelBased && $customerGroupBased && $conditionsBased) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Removes the already applied coupon on the current cart instance
     *
     * @return boolean
     */
    public function remove()
    {
        $cart = Cart::getCart();

        $existingRule = $this->cartRuleCart->findWhere([
            'cart_id' => $cart->id
        ]);

        if ($existingRule->count()) {
            $existingRule->first()->delete();

            foreach ($cart->items as $item) {
                if ($item->discount_amount > 0) {
                    $item->update([
                        'discount_amount' => 0,
                        'base_discount_amount' => 0,
                        'discount_percent' => 0,
                        'coupon_code' => NULL
                    ]);
                }
            }

            $cart->update([
                'coupon_code' => NULL,
                'discount_amount' => 0,
                'base_discount_amount' => 0
            ]);

            Cart::collectTotals();

            return true;
        } else {
            return false;
        }
    }
}