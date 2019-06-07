<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Repositories\CartRuleRepository as CartRule;
use Webkul\Discount\Repositories\CartRuleCartRepository as CartRuleCart;
use Carbon\Carbon;


class Discount
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
     * disable coupon
     */
    protected $disableCoupon = false;

    public function __construct(CartRule $cartRule, CartRuleCart $cartRuleCart)
    {
        $this->cartRule = $cartRule;
        $this->endRuleActive = false;
        $this->cartRuleCart = $cartRuleCart;
    }

    public function applyNonCouponAbleRule()
    {
        if (auth()->guard('customer')->check()) {
            $nonCouponAbleRules = $this->cartRule->findWhere([
                'use_coupon' => 0,
                'status' => 1
            ]);
        } else {
            $nonCouponAbleRules = $this->cartRule->findWhere([
                'use_coupon' => 0,
                'is_guest' => 1,
                'status' => 1
            ]);
        }

        $canBeApplied = array();

        // time based filter
        foreach($nonCouponAbleRules as $rule) {
            $report = $this->checkApplicability($rule);
            $report['rule'] = $rule;

            $passed = 0;
            if ($rule->starts_from != null && $rule->ends_till == null) {
                if (Carbon::parse($rule->starts_from) < now()) {
                    $passed = 1;
                }
            } else if ($rule->starts_from == null && $rule->ends_till != null) {
                if (Carbon::parse($rule->ends_till) > now()) {
                    $passed = 1;
                }
            } else if ($rule->starts_from != null && $rule->ends_till != null) {
                if (Carbon::parse($rule->starts_from) < now() && now() < Carbon::parse($rule->ends_till)) {
                    $passed = 1;
                }
            } else {
                $passed = 1;
            }

            if ($passed) {
                array_push($canBeApplied, $report);
            }
        }

        //min priority
        $minPriority = collect($canBeApplied)->min('priority');

        $canBeApplied = collect($canBeApplied)->where('priority', $minPriority);

        if (count($canBeApplied) > 1) {
            $maxDiscount = collect($canBeApplied)->max('discount');

            $canBeApplied = collect($canBeApplied)->where('discount', $maxDiscount);

            $leastId = 999999999999;
            if (count($canBeApplied) > 1) {
                foreach($canBeApplied as $rule) {
                    if ($rule['rule'] < $leastId) {
                        $leastId = $rule['rule']->id;
                    }
                }
            }
        }
        //find end rule
        return $canBeApplied;
    }

    public function applyCouponAbleRule()
    {
        if (auth()->guard('customer')->check()) {
            $couponAbleRules = $this->cartRule->findWhere([
                'use_coupon' => 1
            ]);
        } else {
            $couponAbleRules = $this->cartRule->findWhere([
                'use_coupon' => 1,
                'is_guest' => 1
            ]);
        }

        // time based filter
        foreach($couponAbleRules as $rule) {
            if ($rule->starts_from != null && $rule->ends_till == null) {
                if (Carbon::parse($rule->starts_from) < now()) {

                }
            } else if ($rule->starts_from == null && $rule->ends_till != null) {
                if (Carbon::parse($rule->ends_till) < now()) {

                }
            } else if ($rule->starts_from != null && $rule->ends_till != null) {
                if (Carbon::parse($rule->starts_from) < now() && now() < Carbon::parse($rule->ends_till)) {
                }
            }
        }

        return $couponAbleRules;
    }

    public function checkApplicability($rule)
    {
        $cart = \Cart::getCart();
        $report = array();
        $result = 0;
        //check conditions
        if ($rule->conditions != null) {
            $conditions = json_decode(json_decode($rule->conditions));
            $test_mode = config('pricerules.test_mode.0');

            if ($test_mode == config('pricerules.test_mode.0')) {
                $result = $this->testIfAllConditionAreTrue($conditions, $cart);
            } else if ($test_mode == config('pricerules.test_mode.1')) {
                $result = $this->testIfAllConditionAreFalse($conditions, $cart);
            } else if ($test_mode == config('pricerules.test_mode.2')) {
                $result = $this->testIfAnyConditionIsTrue($conditions, $cart);
            } else if ($test_mode == config('pricerules.test_mode.3')) {
                $result = $this->testIfAnyConditionIsFalse($conditions, $cart);
            }
        }

        if ($result) {
            $report['conditions'] = true;
        } else {
            if ($rule->conditions == null)
                $report['conditions'] = true;
            else
                $report['conditions'] = false;
        }

        //check endrule
        if ($rule->end_other_rules) {
            $report['end_other_rules'] = true;
        } else {
            $report['end_other_rules'] = false;
        }

        //calculate discount amount
        $action_type = $rule->action_type; // action type used
        $disc_threshold = $rule->disc_threshold; // atleast quantity by default 1 --> may be omitted in near future
        $disc_amount = $rule->disc_amount; // value of discount
        $disc_quantity = $rule->disc_quantity; //max quantity allowed to be discounted

        $amountDiscounted;
        $leastWorthItem = \Cart::leastWorthItem();
        $realQty = $leastWorthItem['quantity'];

        if ($cart->items_qty >= $disc_threshold && $realQty >= $disc_quantity) {
            if ($action_type == config('pricerules.cart.validation.0')) {
                $amountDiscounted = $leastWorthItem['total'] * ($disc_amount / 100);
            } else if ($action_type == config('pricerules.cart.validation.1')) {
                $amountDiscounted = $leastWorthItem['total'] - $disc_amount;

                if ($amountDiscounted < 0) {
                    $amountDiscounted = $leastWorthItem['total'];
                }
            } else if ($action_type == config('pricerules.cart.validation.2')) {
                $amountDiscounted = $disc_amount;
            }
        }

        $report['discount'] = $amountDiscounted;
        $report['priority'] = $rule->priority;

        return $report;
    }

    protected function testIfAllConditionAreTrue($conditions, $cart) {
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
                }
            }
        }

        return $result;
    }
}