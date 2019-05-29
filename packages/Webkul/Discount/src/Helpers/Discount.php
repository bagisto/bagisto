<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Repositories\CartRuleRepository as CartRule;
use Carbon\Carbon;

class Discount
{
    /**
     * To hold the cart rule repository instance
     */
    protected $cartRule;

    /**
     * To hold if end rules are present or not.
     */
    protected $endRuleActive;

    public function __construct(CartRule $cartRule)
    {
        $this->cartRule = $cartRule;
        $this->endRuleActive = false;
    }

    /**
     * Gets the end rules applicable for guests
     * autocheck for customer group and channels
     */
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

    /**
     * Get the non ending rules for the guest
     * and completed auto check for guests and
     * channels
     */
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


    /**
     * Gets the best rules for auth customers
     */
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
                            if (auth()->guard('customer')->user()->customer_group_id == $customerGroup->customer_group_id) {
                                array_push($suitableRules, $rule);
                            }
                        }
                    }
                }
            }
        }

        return $suitableRules;
    }

    /**
     * Gets the end rules for the auth customers
     */
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
                            if (auth()->guard('customer')->user()->customer_group_id == $customerGroup->customer_group_id) {
                                array_push($suitableRules, $rule);
                            }
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

    /**
     * Get the rules to applied automatically
     * depending on user state
     */
    public function toApply()
    {
        if (auth()->guard('customer')->check()) {
            $endRules = $this->getEndRules();

            if (isset($endRules) && count($endRules)) {
                $this->endRuleActive = true;

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
                $this->endRuleActive = true;

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

    /**
     * To apply the coupon code based rules
     */
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

    /**
     * To apply the non coupon based rules
     */
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
                if (! $rule->use_coupon) {
                    array_push($id, $rule);
                }
            }

            if (count($id)) {
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

    public function nonRuleCheck()
    {
        $result = $this->applyNonCouponAble();
        $cart = \Cart::getCart();

        if ($result['end_rule']) {
            $this->endRuleActive = true;
        } else {
            $this->endRuleActive = false;
        }

        $maxImpacts = array();

        if (isset($result['id']) && count($result['id'])) {
            $rules = array();

            if (count($result['id']) > 1) {
                $leastPriority = 999999999999;

                foreach($result['id'] as $rule) {
                    if ($rule->priority < $leastPriority) {
                        array_push($rules, $rule);
                    }
                }
            }

            foreach ($rules as $rule) {
                // All of conditions is/are true
                $result = 1;
                if ($rule->conditions && $rule->conditions != "null") {
                    if ($rule->starts_from != null && $rule->ends_till != null) {
                        if (Carbon::parse($rule->starts_from) < now() && now() < Carbon::parse($rule->ends_till)) {
                            $conditions = json_decode(json_decode($rule->conditions));
                            $test_mode = config('pricerules.test_mode.0');

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
                    } else {
                        $conditions = json_decode(json_decode($rule->conditions));
                        $test_mode = config('pricerules.test_mode.0');

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
                } else {
                    if ($rule->starts_from != null && $rule->ends_till != null) {
                        if (Carbon::parse($rule->starts_from) < now() && now() < Carbon::parse($rule->ends_till)) {
                        } else {
                            $result = 0;
                        }
                    }
                }

                // check all the conditions associated with the rule
                if ($result) {
                    $action_type = $rule->action_type;
                    $disc_threshold = $rule->disc_threshold;
                    $disc_amount = $rule->disc_amount;
                    $disc_quantity = $rule->disc_quantity;

                    $newBaseSubTotal = 0;
                    $newQuantity = 0;

                    if ($cart->items_qty >= $disc_threshold && $disc_quantity > 0) {
                        if ($disc_quantity > 1) {
                            $disc_amount = $disc_amount * $disc_quantity;
                        }
                        // add the time conditions if rule is expired and active then make it in active
                        $leastWorthItem = \Cart::leastWorthItem();
                        $realQty = $leastWorthItem['quantity'];

                        if ($action_type == config('pricerules.cart.validation.0')) {
                            $newBaseSubTotal = $cart->grand_total - ($leastWorthItem['base_total'] * $disc_amount) / 100;
                        } else if ($action_type == config('pricerules.cart.validation.1')) {
                            if ($disc_amount > ($disc_quantity * $leastWorthItem['base_total'])) {
                                $newBaseSubTotal = 0;
                            } else {
                                $newBaseSubTotal = $cart->grand_total - $disc_amount;
                            }
                        } else if ($action_type == config('pricerules.cart.validation.2')) {
                            $newQuantity = $this->cartItem->find($leastWorthItem['id'])->quantity + $disc_amount;
                        } else if ($action_type == config('pricerules.cart.validation.3')) {
                            $newBaseSubTotal = $disc_amount;
                        }

                        //standard returns
                        if ($action_type == config('pricerules.cart.validation.2')) {
                            array_push($maxImpacts, [
                                'id' => $rule->id,
                                'item_id' => $leastWorthItem['id'],
                                'amount_given' => false,
                                'amount' => $newQuantity,
                                'action_type' => $action_type
                            ]);
                        } else {
                            array_push($maxImpacts, [
                                'id' => $rule->id,
                                'item_id' => $leastWorthItem['id'],
                                'amount_given' => true,
                                'amount' => $newBaseSubTotal,
                                'action_type' => $action_type
                            ]);
                        }
                    }
                }
            }
        }

        $leastItemAvg = 999999999999;
        $leastId = 0;
        foreach($maxImpacts as $maxImpact) {
            $minItemPrice = array();

            if ($maxImpact['action_type'] == config('pricerules.cart.validation.2')) {
                $amount = $cart->base_grand_total / $maxImpact['amount'];

                if ($amount < $leastItemAvg) {
                    $leastItemAvg = $amount;
                    $leastId = $maxImpact['id'];
                }
            } else {
                $amount = $maxImpact['amount'] / $cart->items_qty;

                if ($amount < $leastItemAvg) {
                    $leastItemAvg = $amount;
                    $leastId = $maxImpact['id'];
                }
            }
        }

        if ($leastId != 0)
            return $this->cartRule->find($leastId);
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

        // All of conditions is/are true
        $result = 1;
        if ($appliedRule->conditions && $appliedRule->conditions != "null") {
            if ($appliedRule->starts_from != null && $appliedRule->ends_till != null) {
                if (Carbon::parse($appliedRule->starts_from) < now() && now() < Carbon::parse($appliedRule->ends_till)) {
                    $conditions = json_decode(json_decode($appliedRule->conditions));
                    $test_mode = config('pricerules.test_mode.0');

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
            } else {
                $conditions = json_decode(json_decode($rule->conditions));
                $test_mode = config('pricerules.test_mode.0');

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
        } else {
            if ($appliedRule->starts_from != null && $appliedRule->ends_till != null) {
                if (Carbon::parse($appliedRule->starts_from) < now() && now() < Carbon::parse($appliedRule->ends_till)) {
                } else {
                    $result = 0;
                }
            }
        }

        // check all the conditions associated with the rule
        if ($result) {
            $action_type = $rule->action_type;
            $disc_threshold = $rule->disc_threshold;
            $disc_amount = $rule->disc_amount;
            $disc_quantity = $rule->disc_quantity;

            $newBaseSubTotal = 0;
            $newQuantity = 0;

            if ($cart->items_qty >= $disc_threshold && $disc_quantity > 0) {
                if ($disc_quantity > 1) {
                    $disc_amount = $disc_amount * $disc_quantity;
                }
                // add the time conditions if rule is expired and active then make it in active
                $leastWorthItem = \Cart::leastWorthItem();
                $realQty = $leastWorthItem['quantity'];

                if ($action_type == config('pricerules.cart.validation.0')) {
                    $newBaseSubTotal = $cart->grand_total - ($leastWorthItem['base_total'] * $disc_amount) / 100;
                } else if ($action_type == config('pricerules.cart.validation.1')) {
                    if ($disc_amount > ($disc_quantity * $leastWorthItem['base_total'])) {
                        $newBaseSubTotal = 0;
                    } else {
                        $newBaseSubTotal = $cart->grand_total - $disc_amount;
                    }
                } else if ($action_type == config('pricerules.cart.validation.2')) {
                    $newQuantity = $this->cartItem->find($leastWorthItem['id'])->quantity + $disc_amount;
                } else if ($action_type == config('pricerules.cart.validation.3')) {
                    $newBaseSubTotal = $disc_amount;
                }

                if ($action_type == config('pricerules.cart.validation.2')) {
                    return response()->json([
                        'message' => trans('admin::app.promotion.status.coupon-applied'),
                        'action' => $action_type,
                        'amount_given' => false,
                        'amount_payable' => $newQuantity,
                        'amount' => null
                    ]);
                } else {
                    return response()->json([
                        'message' => trans('admin::app.promotion.status.coupon-applied'),
                        'action' => $action_type,
                        'amount_given' => true,
                        'amount_payable' => core()->currency($newBaseSubTotal),
                        'amount' => core()->currency($cart->grand_total - $newBaseSubTotal)
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

    protected function testAllConditionAreFalse($conditions, $cart) {
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

    protected function testAnyConditionIsTrue($conditions, $cart) {
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

    protected function testAnyConditionIsFalse($conditions, $cart) {
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