<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Repositories\CartRuleRepository as CartRule;

class Discount
{
    /**
     * To hold the cart rule repository instance
     */
    protected $cartRule;

    public function __construct(CartRule $cartRule)
    {
        $this->cartRule = $cartRule;
    }

    public function getGuestEndRules()
    {
        $rules = $this->cartRule->findWhere(['status' => 1, 'end_other_rules' => 1, 'is_guest' => 1]);
        $currentChannel = core()->getCurrentChannel();

        $guestRules = array();
        foreach ($rules as $rule) {
            foreach ($rule->channels as $channel) {
                if ($channel->channel_id == $currentChannel->id) {
                    array_push($guestRules, $rule);
                }
            }
        }

        if (count($guestRules) > 1) {
            $leastPriority = 999999999999;
            $leastId = 999999999999;

            foreach ($guestRules as $guestRule) {
                if ($leastPriority >= $guestRule->priority) {
                    $leastPriority = $guestRule->priority;

                    if ($leastId > $guestRule->id) {
                        $leastId = $guestRule->id;
                    }
                }
            }

            return [$this->cartRule->find($leastId)];
        }

        return $guestRules;
    }

    public function getGuestBestRules()
    {
        $rules = $this->cartRule->findWhere(['status' => 1, 'end_other_rules' => 0, 'is_guest' => 1]);
        $currentChannel = core()->getCurrentChannel();

        $guestRules = array();
        foreach ($rules as $rule) {
            foreach ($rule->channels as $channel) {
                if ($channel->channel_id == $currentChannel->id) {
                    array_push($guestRules, $rule);
                }
            }
        }

        return $guestRules;
    }

    public function getBestRules()
    {
        $rules = $this->cartRule->findWhere(['status' => 1, 'end_other_rules' => 0]);
        $currentChannel = core()->getCurrentChannel();

        $suitableRules = array();
        foreach ($rules as $rule) {
            foreach ($rule->channels as $channel) {
                if ($channel->channel_id == $currentChannel->id) {
                    if (auth()->guard('customer')->check()) {
                        foreach ($rule->customer_groups as $customerGroup) {
                            if (auth()->guard('customer')->user()->customer_group_id == $customerGroup->customer_group_id)
                                array_push($suitableRules, $rule);
                        }
                    }
                }
            }
        }

        return $suitableRules;
    }

    public function getEndRules()
    {
        $rules = $this->cartRule->findWhere(['status' => 1, 'end_other_rules' => 1]);
        $currentChannel = core()->getCurrentChannel();

        $suitableRules = array();
        foreach ($rules as $rule) {
            foreach ($rule->channels as $channel) {
                if ($channel->channel_id == $currentChannel->id) {
                    if (auth()->guard('customer')->check()) {
                        foreach ($rule->customer_groups as $customerGroup) {
                            if (auth()->guard('customer')->user()->customer_group_id == $customerGroup->customer_group_id)
                                array_push($suitableRules, $rule);
                        }
                    }
                }
            }
        }

        if (count($suitableRules) > 1) {
            $leastPriority = 999999999999;
            $leastId = 999999999999;

            foreach($suitableRules as $suitableRule) {
                if ($leastPriority >= $suitableRule->priority) {
                    $leastPriority = $suitableRule->priority;

                    if ($leastId > $suitableRule->id) {
                        $leastId = $suitableRule->id;
                    }
                }
            }

            return [$this->cartRule->find($leastId)];
        }

        return $suitableRules;
    }

    public function toApply()
    {
        if (auth()->guard('customer')->check()) {
            $endRules = $this->getEndRules();

            if (isset($endRules) && count($endRules)) {
                return [
                    'end_rule' => true,
                    'rule' => $endRules,
                    'count' => count($endRules)
                ];
            } else {
                $bestRules = $this->getBestRules();

                return [
                    'end_rule' => false,
                    'rule' => $bestRules,
                    'count' => count($bestRules)
                ];
            }
        } else {
            $endRules = $this->getGuestEndRules();

            if (isset($endRules) && count($endRules)) {
                return [
                    'end_rule' => true,
                    'rule' => $endRules,
                    'count' => count($endRules)
                ];
            } else {
                $bestRules = $this->getGuestBestRules();

                return [
                    'end_rule' => false,
                    'rule' => $bestRules,
                    'count' => count( $bestRules)
                ];
            }
        }
    }

    public function applyCouponAble()
    {
        $rules = $this->toApply();

        if ($rules['end_rule']) {
            $rules = $rules['rule'];
            $id = array();

            foreach($rules as $rule) {
                if ($rule->use_coupon) {
                    array_push($id, $rule);
                }
            }

            if (count($id)) {
                return [
                    'couponable' => true,
                    'id' => $id
                ];
            } else {
                return [
                    'couponable' => false,
                    'id' => null
                ];
            }
        } else {
            $rules = $rules['rule'];
            $id = array();

            foreach ($rules as $rule) {
                if ($rule->use_coupon) {
                    array_push($id, $rule);
                }
            }

            if (count($id)) {
                return [
                    'couponable' => true,
                    'id' => $id
                ];
            } else {
                return [
                    'couponable' => false,
                    'id' => null
                ];
            }
        }
    }

    public function applyNonCouponAble()
    {
        $rules = $this->toApply();

        if (! $rules['end_rule']) {
            $rules = $rules['rule'];
            $id = array();

            foreach ($rules as $rule) {
                if (! $rule->use_coupon) {
                    array_push($id, $rule);
                }
            }

            if (count($id)) {
                return [
                    'end_rule' => false,
                    'noncouponable' => true,
                    'id' => $id
                ];
            } else {
                return [
                    'end_rule' => false,
                    'noncouponable' => false,
                    'id' => null
                ];
            }
        } else {
            $rules = $rules['rule'];

            $id = array();

            foreach ($rules as $rule) {
                if ($rule->use_coupon) {
                    array_push($id, $rule);
                }
            }

            if (!$rule->use_coupon) {
                return [
                    'end_rule' => true,
                    'noncouponable' => true,
                    'id' => $id
                ];
            } else {
                return [
                    'end_rule' => true,
                    'noncouponable' => false,
                    'id' => null
                ];
            }
        }
    }

    // works automatically on the basis of no conditions
    public function checkNonCouponConditions()
    {
        $rules = $this->applyNonCouponAble();

        return $rules;
    }

    public function ruleCheck($code)
    {
        $rules = $this->applyCouponAble();
        $appliedRule = null;
        $coupons = [];

        foreach($rules['id'] as $rule) {
            array_push($coupons, $rule->coupons->code);
            if ($rule->use_coupon && $rule->auto_generation == 0) {
                if ($rule->coupons->code == $code) {
                    $appliedRule = $rule;

                    break;
                } else {
                    continue;
                }
            }
        }

        if (! isset($appliedRule)) {
            return response()->json(['message' => trans('admin::app.promotion.status.no-coupon')], 200);
        }

        $cart = \Cart::getCart();

        //all of conditions is/are true
        $result = null;
        if ($appliedRule->conditions) {
            $conditions = json_decode(json_decode($appliedRule->conditions));

            $shipping_address = $cart->getShippingAddressAttribute();

            $shipping_method = $cart->shipping_method;
            $shipping_country = $shipping_address->country;
            $shipping_state = $shipping_address->state;
            $shipping_postcode = $shipping_address->postcode;
            $shipping_city = $shipping_address->city;

            $payment_method = $cart->payment->method;
            $sub_total = $cart->base_sub_total;

            $total_items = $cart->items_qty;
            $total_weight = 0;

            foreach($cart->items as $item) {
                $total_weight = $total_weight + $item->base_total_weight;
            }

            $test_mode = config('pricerules.test_mode.0');
            $test_conditions = config('pricerules.cart.conditions');

            if ($test_mode == config('pricerules.test_mode.0')) {
                $result = $this->testAllConditionAreTrue($conditions, $cart);
            } else if ($test_mode == config('pricerules.test_mode.1')) {
                $result = $this->testAllConditionAreFalse($conditions, $cart);
            } else if ($test_mode == config('pricerules.test_mode.2')) {
                $result = $this->testAnyConditionIsTrue($conditions, $cart);
            } else if ($test_mode == config('pricerules.test_mode.3')) {
                $result = $this->testAbyConditionIsFalse($conditions, $cart);
            }
        }

        // check all the conditions associated with the rule
        if ($result) {
            // if (isset($appliedRule) && $appliedRule->starts_from == null) {
            $action_type = $appliedRule->action_type;
            $disc_threshold = $appliedRule->disc_threshold;
            $disc_amount = $appliedRule->disc_amount;
            $disc_quantity = $appliedRule->disc_quantity;

            $newBaseSubTotal = 0;
            $newQuantity = 0;

            if ($cart->items_qty >= $disc_threshold) {
                // add the time conditions if rule is expired and active then make it in active
                $leastWorthItem = \Cart::leastWorthItem();

                if ($action_type == config('pricerules.cart.validation.0')) {
                    $newBaseSubTotal = ($leastWorthItem['base_total'] * $disc_amount) / 100;
                } else if ($action_type == config('pricerules.cart.validation.1')) {
                    $newBaseSubTotal = $leastWorthItem['base_total'] - $disc_amount;
                } else if ($action_type == config('pricerules.cart.validation.2')) {
                    $newQuantity = $this->cartItem->find($leastWorthItem['id'])->quantity + $disc_amount;
                } else if ($action_type == config('pricerules.cart.validation.3')) {
                    $base_total = $disc_amount;
                }

                if ($action_type == config('pricerules.cart.validation.2')) {
                    return response()->json([
                        'message' => trans('admin::app.promotion.status.coupon-applied'),
                        'action' => $action_type,
                        'amount_given' => false,
                        'amount' => $newQuantity
                    ]);
                } else {
                    return response()->json([
                        'message' => trans('admin::app.promotion.status.coupon-applied'),
                        'action' => $action_type,
                        'amount_given' => true,
                        'amount' => core()->currency($newBaseSubTotal)
                    ]);
                }
            } else {
                return response()->json([
                    'message' => trans('admin::app.promotion.status.coupon-failed'),
                    'action' => $action_type,
                    'amount_given' => null,
                    'amount' => null,
                    'least_value_item' => null
                ]);
            }
        } else {
            return response()->json([
                'message' => trans('admin::app.promotion.status.coupon-failed'),
                'action' => null,
                'amount_given' => null,
                'amount' => null,
                'least_value_item' => null
            ]);
        }
    }

    protected function testAllConditionAreTrue($conditions, $cart) {
        $shipping_address = $cart->getShippingAddressAttribute();

        $shipping_method = $cart->shipping_method;
        $shipping_country = $shipping_address->country;
        $shipping_state = $shipping_address->state;
        $shipping_postcode = $shipping_address->postcode;
        $shipping_city = $shipping_address->city;

        $payment_method = $cart->payment->method;
        $sub_total = $cart->base_sub_total;

        $total_items = $cart->items_qty;
        $total_weight = 0;

        foreach($cart->items as $item) {
            $total_weight = $total_weight + $item->base_total_weight;
        }

        $test_mode = config('pricerules.test_mode.0');
        $test_conditions = config('pricerules.cart.conditions');

        $result = 1;
        foreach ($conditions as $condition) {
            $actual_value = ${$condition->attribute};
            $test_value = $condition->value;
            $test_condition = $condition->condition;

            if ($condition->type == 'numeric' || $condition->type == 'string' || $condition->type == 'text') {
                if ($test_condition == '=') {
                    if ($actual_value != $test_value) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '>=') {
                    if (! ($actual_value >= $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '<=') {
                    if (! ($actual_value <= $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '>') {
                    if (! ($actual_value > $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '<') {
                    if (! ($actual_value < $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '{}') {
                    if (! str_contains($actual_value, $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '!{}') {
                    if (str_contains($actual_value, $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '()') {
                    // dd($test_condition);
                } else if ($test_condition == '!()') {
                    // dd($test_condition);
                }
            }
                // else if ($condition->type == 'boolean') {
                // if ($test_conditions[$condition->type][$test_condition]) {
                //     if ($test_condition == 0) {
                //         dd($test_condition);
                //     } else if ($test_condition == 1) {
                //         dd($test_condition);
                //     }
                // }
                // }
        }

        return $result;
    }

    protected function testAllConditionAreFalse($condition, $cart) {
        $shipping_address = $cart->getShippingAddressAttribute();

        $shipping_method = $cart->shipping_method;
        $shipping_country = $shipping_address->country;
        $shipping_state = $shipping_address->state;
        $shipping_postcode = $shipping_address->postcode;
        $shipping_city = $shipping_address->city;

        $payment_method = $cart->payment->method;
        $sub_total = $cart->base_sub_total;

        $total_items = $cart->items_qty;
        $total_weight = 0;

        foreach($cart->items as $item) {
            $total_weight = $total_weight + $item->base_total_weight;
        }

        $test_mode = config('pricerules.test_mode.0');
        $test_conditions = config('pricerules.cart.conditions');
        $result = 1;

        foreach ($conditions as $condition) {
            $actual_value = ${$condition->attribute};
            $test_value = $condition->value;
            $test_condition = $condition->condition;

            if ($condition->type == 'numeric' || $condition->type == 'string' || $condition->type == 'text') {
                if ($test_condition == '=') {
                    if (! ($actual_value != $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '>=') {
                    if (($actual_value >= $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '<=') {
                    if (($actual_value <= $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '>') {
                    if (($actual_value > $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '<') {
                    if (($actual_value < $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '{}') {
                    if (str_contains($actual_value, $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '!{}') {
                    if (! str_contains($actual_value, $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '()') {
                    // dd($test_condition);
                } else if ($test_condition == '!()') {
                    // dd($test_condition);
                }
            }
                // else if ($condition->type == 'boolean') {
                // if ($test_conditions[$condition->type][$test_condition]) {
                //     if ($test_condition == 0) {
                //         dd($test_condition);
                //     } else if ($test_condition == 1) {
                //         dd($test_condition);
                //     }
                // }
                // }
        }

        return $result;
    }

    protected function testAnyConditionIsTrue($condition, $cart) {
        $shipping_address = $cart->getShippingAddressAttribute();

        $shipping_method = $cart->shipping_method;
        $shipping_country = $shipping_address->country;
        $shipping_state = $shipping_address->state;
        $shipping_postcode = $shipping_address->postcode;
        $shipping_city = $shipping_address->city;

        $payment_method = $cart->payment->method;
        $sub_total = $cart->base_sub_total;

        $total_items = $cart->items_qty;
        $total_weight = 0;

        foreach($cart->items as $item) {
            $total_weight = $total_weight + $item->base_total_weight;
        }

        $test_mode = config('pricerules.test_mode.0');
        $test_conditions = config('pricerules.cart.conditions');
        $result = 1;

        foreach ($conditions as $condition) {
            $actual_value = ${$condition->attribute};
            $test_value = $condition->value;
            $test_condition = $condition->condition;

            if ($condition->type == 'numeric' || $condition->type == 'string' || $condition->type == 'text'){
                if ($test_condition == '=') {
                    if ($actual_value == $test_value) {
                        break;
                    }
                } else if ($test_condition == '>=') {
                    if ($actual_value >= $test_value) {
                        break;
                    }
                } else if ($test_condition == '<=') {
                    if ($actual_value <= $test_value) {
                        break;
                    }
                } else if ($test_condition == '>') {
                    if ($actual_value > $test_value) {
                        break;
                    }
                } else if ($test_condition == '<') {
                    if ($actual_value < $test_value) {
                        break;
                    }
                } else if ($test_condition == '{}') {
                    if (str_contains($actual_value, $test_value)) {
                        break;
                    }
                } else if ($test_condition == '!{}') {
                    if (! (! str_contains($actual_value, $test_value))) {
                        break;
                    }
                } else if ($test_condition == '()') {
                    // dd($test_condition);
                } else if ($test_condition == '!()') {
                    // dd($test_condition);
                } else {
                    $result = 0;
                }
            }
        }

        return $result;
    }

    protected function testAnyConditionIsFalse($condition, $cart) {
        $shipping_address = $cart->getShippingAddressAttribute();

        $shipping_method = $cart->shipping_method;
        $shipping_country = $shipping_address->country;
        $shipping_state = $shipping_address->state;
        $shipping_postcode = $shipping_address->postcode;
        $shipping_city = $shipping_address->city;

        $payment_method = $cart->payment->method;
        $sub_total = $cart->base_sub_total;

        $total_items = $cart->items_qty;
        $total_weight = 0;

        foreach($cart->items as $item) {
            $total_weight = $total_weight + $item->base_total_weight;
        }

        $test_mode = config('pricerules.test_mode.0');
        $test_conditions = config('pricerules.cart.conditions');
        $result = 1;

        foreach ($conditions as $condition) {
            $actual_value = ${$condition->attribute};
            $test_value = $condition->value;
            $test_condition = $condition->condition;

            if ($condition->type == 'numeric' || $condition->type == 'string' || $condition->type == 'text'){
                if ($test_condition == '=') {
                    if ($actual_value != $test_value) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '>=') {
                    if (! ($actual_value >= $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '<=') {
                    if (! ($actual_value <= $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '>') {
                    if (! ($actual_value > $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '<') {
                    if ((! $actual_value < $test_value)) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '{}') {
                    if (! (str_contains($actual_value, $test_value))) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '!{}') {
                    if (! (! str_contains($actual_value, $test_value))) {
                        $result = 0;

                        break;
                    }
                } else if ($test_condition == '()') {
                    // dd($test_condition);
                } else if ($test_condition == '!()') {
                    // dd($test_condition);
                } else {
                    $result = 0;
                }
            }
        }

        return $result;
    }
}