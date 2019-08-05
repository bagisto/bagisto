<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Repositories\CartRuleRepository as CartRule;
use Webkul\Discount\Repositories\CartRuleCartRepository as CartRuleCart;
use Carbon\Carbon;
use Cart;

abstract class Discount
{
    /**
     * To hold the cart rule repository instance
     */
    protected $cartRule;

    /**
     * To hold the cart rule cart repository instance
     */
    protected $cartRuleCart;

    /**
     * To hold if end rules are present or not.
     */
    protected $endRuleActive = false;

    /**
     * To hold the rule classes
     */
    protected $rules;

    public function __construct(CartRule $cartRule, CartRuleCart $cartRuleCart)
    {
        $this->cartRule = $cartRule;

        $this->cartRuleCart = $cartRuleCart;

        $this->rules = config('discount-rules');
    }

    /**
     * Abstract method apply
     */
    abstract public function apply($code);

    /**
     * To find all the suitable rules that can be applied on the current cart
     *
     * @return collection $rules
     */
    public function getApplicableRules($usecoupon = false)
    {
        $channel = core()->getCurrentChannel()->id;

        $rules = $this->cartRule->findWhere([
            'use_coupon' => 0,
            'status' => 1
        ]);

        $filteredRules = collect();

        // time based constraints
        foreach ($rules as $rule) {
            if ($this->checkApplicability($rule)) {
                if ($rule->starts_from != null && $rule->ends_till == null) {
                    if (Carbon::parse($rule->starts_from) < now()) {
                        $rule->impact = $this->calculateImpact($rule);

                        $filteredRules->push($rule);
                    }
                } else if ($rule->starts_from == null && $rule->ends_till != null) {
                    if (Carbon::parse($rule->ends_till) > now()) {
                        $rule->impact = $this->calculateImpact($rule);

                        $filteredRules->push($rule);
                    }
                } else if ($rule->starts_from != null && $rule->ends_till != null) {
                    if (Carbon::parse($rule->starts_from) < now() && now() < Carbon::parse($rule->ends_till)) {
                        $rule->impact = $this->calculateImpact($rule);

                        $filteredRules->push($rule);
                    }
                } else {
                    $rule->impact = $this->calculateImpact($rule);

                    $filteredRules->push($rule);
                }
            }
        }

        return $filteredRules;
    }

    /**
     * To find that one rule that is going to be applied on the current cart
     *
     * @param Collection $rules
     *
     * @return Object
     */
    public function breakTie($rules)
    {
        dd($rules);
        // // priority criteria
        // $prioritySorted = collect();
        // $leastPriority = 999999999999;

        // foreach ($rules as $rule) {
        //     if ($rule->priority <= $leastPriority) {
        //         $leastPriority = $rule->priority;

        //         $prioritySorted->push($rule);
        //     }
        // }

        // // end rule criteria with end rule
        // $endRules = collect();

        // if (count($prioritySorted) > 1) {
        //     foreach ($prioritySorted as $prioritySortedRule) {
        //         if ($prioritySortedRule->end_other_rules) {
        //             $endRules->push($prioritySortedRule);
        //         }
        //     }
        // } else {
        //     return $prioritySorted;
        // }

        // // max impact criteria with end rule
        // $maxImpacts = collect();

        // if ($endRules->count()) {
        //     $this->endRuleActive = true;

        //     if (count($endRules) == 1) {
        //         return $endRules->first();
        //     }

        //     $maxImpact = 0;

        //     foreach ($endRules as $endRule) {
        //         if ($endRule->impact->discount >= $maxImpact) {
        //             $maxImpact = $endRule->impact->discount;

        //             $maxImpacts->push($endRule);
        //         }
        //     }

        //     // oldest and max impact criteria
        //     $leastId = 999999999999;
        //     $leastIdImpactIndex = 0;

        //     if ($maxImpacts->count() > 1) {
        //         foreach ($maxImpacts as $index => $maxImpactRule) {
        //             if ($maxImpactRule->id < $leastId) {
        //                 $leastId = $maxImpactRule->id;

        //                 $leastIdImpactIndex = $index;
        //             }
        //         }

        //         return $maxImpacts[$leastIdImpactIndex];
        //     } else {
        //         return $maxImpacts;
        //     }
        // }

        // if (count($prioritySorted) > 1) {
        //     $maxImpact = 0;
        //     $maxImpactRules = collect();

        //     foreach ($prioritySorted as $prioritySortedRule) {
        //         if ($prioritySortedRule->impact->discount >= $maxImpact) {
        //             $maxImpact = $prioritySortedRule->impact->discount;

        //             $maxImpactRules->push($prioritySortedRule);
        //         }
        //     }

        //     $maxImpactRulesRe = collect();

        //     foreach ($maxImpactRules as $maxImpactRule) {
        //         if ($maxImpactRule->impact->discount == $maxImpact) {
        //             $maxImpactRulesRe->push($maxImpactRule);
        //         }
        //     }

        //     // oldest and max impact criteria
        //     $leastId = 999999999999;
        //     $leastIdImpactIndex = 0;

        //     if ($maxImpactRulesRe->count() > 1) {
        //         foreach ($maxImpactRulesRe as $index => $maxImpactRule) {
        //             if ($maxImpactRule->id < $leastId) {
        //                 $leastId = $maxImpactRule->id;

        //                 $leastIdImpactIndex = $index;
        //             }
        //         }

        //         return $maxImpactRulesRe[$leastIdImpactIndex];
        //     } else {
        //         return $maxImpactRulesRe->first();
        //     }
        // } else {
        //     return $prioritySorted->first();
        // }
    }

    /**
     * To calculate the impact of the rule
     *
     * @return collection
     */
    public function calculateImpact($rule)
    {
        $impact = $this->getActionInstance($rule);

        $outcome = $impact->calculate($rule);

        return $outcome;
    }

    /**
     * Return the instance of the related rule's action type
     *
     * @return object
     */
    public function getActionInstance($rule)
    {
        $actionType = new $this->rules['cart'][$rule->action_type];

        return $actionType;
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

        $partialMatch = 0;

        if ($rule->uses_attribute_conditions) {
            $productIDs = explode(',', $rule->product_ids);

            foreach ($productIDs as $productID) {
                foreach ($cart->items as $item) {
                    if ($item->product_id == $productID) {
                        $partialMatch = 1;
                    }
                }
            }
        }

        if ($channelBased && $customerGroupBased && $conditionsBased) {
            if ($rule->uses_attribute_conditions == 1 && $partialMatch) {
                return true;
            } else if ($rule->uses_attribute_conditions == 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Save the rule in the CartRule for current cart instance
     *
     * @param CartRule $rule
     *
     * @return boolean
     */
    public function save($rule)
    {
        $cart = \Cart::getCart();

        // create or update
        $existingRule = $this->cartRuleCart->findWhere([
            'cart_id' => $cart->id
        ]);

        // if ($rule->use_coupon) {
        //     $this->resetShipping($cart);
        // }

        if (count($existingRule)) {
            if ($existingRule->first()->cart_rule_id != $rule->id) {
                $existingRule->first()->update([
                    'cart_rule_id' => $rule->id
                ]);

                $this->clearDiscount();

                $this->updateCartItemAndCart($rule);

                // if ($rule->use_coupon) {
                //     $this->checkOnShipping($cart);
                // }

                return true;
            } else {
                $this->clearDiscount();

                $this->updateCartItemAndCart($rule);
            }
        } else {
            $this->cartRuleCart->create([
                'cart_id' => $cart->id,
                'cart_rule_id' => $rule->id
            ]);

            $this->clearDiscount();

            $this->updateCartItemAndCart($rule);

            if ($rule->use_coupon) {
                $this->checkOnShipping($cart);
            }

            return true;
        }

        return false;
    }

    /**
     * Checks whether rule is getting applied on shipping or not
     */
    public function checkOnShipping($cart)
    {
        if (! isset($cart->selected_shipping_rate)) {
            return false;
        }

        $shippingRate = config('carriers')[$cart->selected_shipping_rate->carrier]['class'];

        $actualShippingRate = new $shippingRate;
        $actualShippingRate = $actualShippingRate->calculate();

        if (is_array($actualShippingRate)) {
            foreach ($actualShippingRate as $actualRate) {
                if ($actualRate->method == $cart->selected_shipping_rate->method) {
                    $actualShippingRate = $actualRate;

                    break;
                }
            }
        }

        $actualShippingPrice = $actualShippingRate->price;
        $actualShippingBasePrice = $actualShippingRate->base_price;

        $alreadyAppliedCartRuleCart = $this->cartRuleCart->findWhere([
            'cart_id' => $cart->id
        ]);

        if (count($alreadyAppliedCartRuleCart)) {
            $this->resetShipping($cart);

            $alreadyAppliedRule = $alreadyAppliedCartRuleCart->first()->cart_rule;

            $cartShippingRate = $cart->selected_shipping_rate;

            if (isset($cartShippingRate)) {
                if ($cartShippingRate->base_price < $actualShippingBasePrice) {
                    return false;
                } else {
                    $this->applyOnShipping($alreadyAppliedRule, $cart);
                }
            } else {
                $this->applyOnShipping($alreadyAppliedRule, $cart);
            }
        } else {
            $this->resetShipping($cart);
        }
    }

    /**
     * Apply on shipping
     *
     * @return void
     */
    public function applyOnShipping($appliedRule, $cart)
    {
        // $cart = \Cart::getCart();

        // if (isset($cart->selected_shipping_rate)) {
        //     if ($appliedRule->free_shipping && $cart->selected_shipping_rate->base_price > 0) {
        //         $cart->selected_shipping_rate->update([
        //             'price' => 0,
        //             'base_price' => 0
        //         ]);
        //     } else if ($appliedRule->free_shipping == 0 && $appliedRule->apply_to_shipping && $cart->selected_shipping_rate->base_price > 0) {
        //         $actionType = config('discount-rules')[$appliedRule->action_type];

        //         if ($appliedRule->apply_to_shipping) {
        //             $actionInstance = new $actionType;

        //             $discountOnShipping = $actionInstance->calculateOnShipping($cart);

        //             $discountOnShipping = ($discountOnShipping / 100) * $cart->selected_shipping_rate->base_price;

        //             $cart->selected_shipping_rate->update([
        //                 'price' => $cart->selected_shipping_rate->price - core()->convertPrice($discountOnShipping, $cart->cart_currency_code),
        //                 'base_price' => $cart->selected_shipping_rate->base_price - $discountOnShipping
        //             ]);
        //         }
        //     }
        // }
    }

    /**
     * Resets the shipping for the current items in the cart
     *
     * @return void
     */
    public function resetShipping($cart)
    {
        $cart = \Cart::getCart();

        if (isset($cart->selected_shipping_rate->carrier)) {
            $shippingRate = config('carriers')[$cart->selected_shipping_rate->carrier]['class'];

            $actualShippingRate = new $shippingRate;
            $actualShippingRate = $actualShippingRate->calculate();

            if (is_array($actualShippingRate)) {
                foreach($actualShippingRate as $actualRate) {
                    if ($actualRate->method == $cart->selected_shipping_rate->method) {
                        $actualShippingRate = $actualRate;

                        break;
                    }
                }
            }

            $actualShippingPrice = $actualShippingRate->price;
            $actualShippingBasePrice = $actualShippingRate->base_price;
            $cartShippingRate = $cart->selected_shipping_rate;

            $cartShippingRate->update([
                'price' => $actualShippingPrice,
                'base_price' => $actualShippingBasePrice
            ]);
        }
    }

    /**
     * Update discount for least worth item
     *
     * @return boolean
     */
    public function updateCartItemAndCart($rule)
    {
        $cart = Cart::getCart();

        $cartItems = $cart->items;

        $impact = $rule->impact;

        foreach ($cart->items as $item) {
            foreach ($impact as $perItemImpact) {
                if ($item->id == $perItemImpact['item_id']) {
                    if ($perItemImpact['discount'] > 0) {
                        $item->update([
                            'discount_amount' => core()->convertPrice($perItemImpact['discount'], $cart->cart_currency_code),
                            'base_discount_amount' => $perItemImpact['discount']
                        ]);

                        if ($item->id == $perItemImpact['item_id'] && $perItemImpact['discount'] > 0) {
                            $item->update([
                                'discount_percent' => $rule->discount_amount
                            ]);
                        }
                    }

                    // save coupon if rule use it
                    if ($rule->use_coupon) {
                        $coupon = $rule->coupons->code;

                        $item->update([
                            'coupon_code' => $coupon
                        ]);
                    }
                }
            }
        }

        if ($rule->use_coupon) {
            $cart->update([
                'coupon_code' => $rule->coupons->code
            ]);
        }

        Cart::collectTotals();

        return true;
    }

    /**
     * Removes any cart rule from the current cart instance
     *
     * @return void
     */
    public function clearDiscount()
    {
        $cart = Cart::getCart();

        $cartItems = $cart->items;

        foreach ($cartItems as $item) {
            $item->update([
                'coupon_code' => NULL,
                'discount_percent' => 0,
                'discount_amount' => 0,
                'base_discount_amount' => 0
            ]);
        }

        $cart->update([
            'coupon_code' => NULL,
            'discount_amount' => 0,
            'base_discount_amount' => 0
        ]);

        return true;
    }

    /**
     * Validate the currently applied cart rule
     *
     * @return boolean
     */
    public function validateRule($rule)
    {
        $applicability = $this->checkApplicability($rule);

        if ($applicability) {
            if ($rule->status) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Retreives all the payment methods from application config
     *
     * @return array
     */
    public function getPaymentMethods()
    {
        $paymentMethods = config('paymentmethods');

        return $paymentMethods;
    }

    /**
     * Retreives all the shippin methods from the application config
     *
     * @return array
     */
    public function getShippingMethods()
    {
        $shippingMethods = config('carriers');

        return $shippingMethods;
    }

    /**
     * Checks the rule against the current cart instance whether rule conditions are applicable
     * or not
     *
     * @return boolean
     */
    protected function testIfAllConditionAreTrue($conditions, $cart)
    {
        $paymentMethods = $this->getPaymentMethods();

        $shippingMethods = $this->getShippingMethods();

        array_pop($conditions);

        $shipping_address = $cart->getShippingAddressAttribute() ?? null;

        $shipping_method = $cart->selected_shipping_rate->method_title ?? null;

        $shipping_country = $shipping_address->country ?? null;

        $shipping_state = $shipping_address->state ?? null;

        $shipping_postcode = $shipping_address->postcode ?? null;

        $shipping_city = $shipping_address->city ?? null;

        if (isset($cart->payment)) {
            $payment_method = $paymentMethods[$cart->payment->method]['title'];
        } else {
            $payment_method = null;
        }

        $sub_total = $cart->base_sub_total;

        $total_items = $cart->items_qty;

        $total_weight = 0;

        foreach ($cart->items as $item) {
            $total_weight = $total_weight + $item->base_total_weight;
        }

        $result = true;

        foreach ($conditions as $condition) {
            if (isset($condition->attribute)) {
                $actual_value = ${$condition->attribute};

            } else {
                $result = false;
            }

            if (isset($condition->value)) {
                $test_value = $condition->value;

            } else {
                $result = false;
            }

            if (isset($condition->condition)) {
                $test_condition = $condition->condition;
            }
            else {
                $result = false;
            }

            if (isset($condition->type) && ($condition->type == 'numeric' || $condition->type == 'string' || $condition->type == 'text')) {
                if ($condition->type == 'string') {
                    $actual_value = strtolower($actual_value);

                    $test_value = strtolower($test_value);
                }

                if ($test_condition == '=') {
                    if ($actual_value != $test_value) {
                        $result = false;

                        break;
                    }
                } else if ($test_condition == '>=') {
                    if (! ($actual_value >= $test_value)) {
                        $result = false;

                        break;
                    }
                } else if ($test_condition == '<=') {
                    if (! ($actual_value <= $test_value)) {
                        $result = false;

                        break;
                    }
                } else if ($test_condition == '>') {
                    if (! ($actual_value > $test_value)) {
                        $result = false;

                        break;
                    }
                } else if ($test_condition == '<') {
                    if (! ($actual_value < $test_value)) {
                        $result = false;

                        break;
                    }
                } else if ($test_condition == '{}') {
                    if (! str_contains($actual_value, $test_value)) {
                        $result = false;

                        break;
                    }
                } else if ($test_condition == '!{}') {
                    if (str_contains($actual_value, $test_value)) {
                        $result = false;

                        break;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Checks the rule against the current cart instance whether rule conditions are applicable
     * or not
     *
     * @return boolean
     */
    protected function testIfAnyConditionIsTrue($conditions, $cart)
    {
        $paymentMethods = $this->getPaymentMethods();

        $shippingMethods = $this->getShippingMethods();

        array_pop($conditions);

        $result = false;

        $shipping_address = $cart->getShippingAddressAttribute() ?? null;

        $shipping_method = $cart->selected_shipping_rate->method_title ?? null;

        $shipping_country = $shipping_address->country ?? null;

        $shipping_state = $shipping_address->state ?? null;

        $shipping_postcode = $shipping_address->postcode ?? null;

        $shipping_city = $shipping_address->city ?? null;

        if (isset($cart->payment)) {
            $payment_method = $paymentMethods[$cart->payment->method]['title'];
        } else {
            $payment_method = null;
        }

        $sub_total = $cart->base_sub_total;

        $total_items = $cart->items_qty;

        $total_weight = 0;

        foreach($cart->items as $item) {
            $total_weight = $total_weight + $item->base_total_weight;
        }

        foreach ($conditions as $condition) {
            if (isset($condition->attribute)) {
                $actual_value = ${$condition->attribute};

            } else {
                $result = false;
            }

            if (isset($condition->value)) {
                $test_value = $condition->value;

            } else {
                $result = false;
            }

            if (isset($condition->condition)) {
                $test_condition = $condition->condition;
            }
            else {
                $result = false;
            }

            if ($condition->type == 'numeric' || $condition->type == 'string' || $condition->type == 'text') {
                if ($condition->type == 'string') {
                    $actual_value = strtolower($actual_value);

                    $test_value = strtolower($test_value);
                }

                if ($test_condition == '=') {
                    if ($actual_value == $test_value) {
                        $result = true;

                        break;
                    }
                } else if ($test_condition == '>=') {
                    if ($actual_value >= $test_value) {
                        $result = true;

                        break;
                    }
                } else if ($test_condition == '<=') {
                    if ($actual_value <= $test_value) {
                        $result = true;

                        break;
                    }
                } else if ($test_condition == '>') {
                    if ($actual_value > $test_value) {
                        $result = true;

                        break;
                    }
                } else if ($test_condition == '<') {
                    if ($actual_value < $test_value) {
                        $result = true;

                        break;
                    }
                } else if ($test_condition == '{}') {
                    if (! str_contains($actual_value, $test_value)) {
                        $result = true;

                        break;
                    }
                } else if ($test_condition == '!{}') {
                    if (str_contains($actual_value, $test_value)) {
                        $result = true;

                        break;
                    }
                }
            }
        }

        return $result;
    }
}