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
                $report['used_coupon'] = false;
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
                    if ($rule['rule']->id < $leastId) {
                        $leastId = $rule['rule']->id;
                    }
                }

                // fighting the edge case for non couponable discount rule
                foreach($canBeApplied as $rule) {
                    if ($rule['rule']->id == $leastId) {
                        $rule['used_coupon'] = false;

                        $this->save($rule['rule']);

                        return $rule;
                    }
                }
            }
        }

        if ($canBeApplied->count()) {
            $this->save($canBeApplied[0]['rule']);

            return $canBeApplied[0];
        } else {
            return false;
        }
    }

    public function applyCouponAbleRule($code)
    {
        if (auth()->guard('customer')->check()) {
            $couponAbleRules = $this->cartRule->findWhere([
                'use_coupon' => 1,
                'status' => 1
            ]);
        } else {
            $couponAbleRules = $this->cartRule->findWhere([
                'use_coupon' => 1,
                'is_guest' => 1,
                'status' => 1
            ]);
        }

        $rule;
        foreach ($couponAbleRules as $couponAbleRule) {
            if ($couponAbleRule->coupons->code == $code) {
                $rule = $couponAbleRule;
            }
        }

        $useCouponable = false;
        if (isset($rule)) {
            $canBeApplied = array();

            // time based filter
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

                $useCouponable = true;
                $alreadyAppliedRule = $this->cartRuleCart->findWhere([
                    'cart_id' => \Cart::getCart()->id
                ]);

                if ($alreadyAppliedRule->count() && ! ($alreadyAppliedRule->first()->cart_rule->priority < $canBeApplied[0]['rule']->priority)) {
                    unset($report);

                    $alreadyAppliedRule = $alreadyAppliedRule->first()->cart_rule;

                    // analyze impact
                    $report = $this->checkApplicability($alreadyAppliedRule);
                    $report['rule'] = $alreadyAppliedRule;

                    array_push($canBeApplied, $report);

                    //min priority
                    $minPriority = collect($canBeApplied)->min('priority');
                    //min priority rule
                    $canBeApplied = collect($canBeApplied)->where('priority', $minPriority);

                    if (count($canBeApplied) > 1) {
                        $maxDiscount = collect($canBeApplied)->max('discount');

                        $canBeApplied = collect($canBeApplied)->where('discount', $maxDiscount);

                        $leastId = 999999999999;
                        if (count($canBeApplied) > 1) {
                            foreach($canBeApplied as $rule) {
                                if ($rule['rule']->id < $leastId) {
                                    $leastId = $rule['rule']->id;
                                }
                            }

                            // fighting the edge case for couponable discount rule
                            foreach($canBeApplied as $rule) {
                                if ($rule['rule']->id == $leastId) {
                                    if($rule['rule']->use_coupon) {
                                        $useCouponable = true;
                                    }
                                }
                            }
                        } else {
                            if ($canBeApplied[0]['rule']->use_coupon) {
                                $useCouponable = true;
                            }
                        }
                    } else if (count($canBeApplied)) {
                        if ($canBeApplied[0]['rule']->use_coupon) {
                            $useCouponable = true;
                        }
                    }

                    if ($alreadyAppliedRule->end_other_rules) {
                        $useCouponable = false;
                    }
                }
            }
        }

        $canBeApplied = array();
        if ($useCouponable) {
            $report = $this->checkApplicability($rule);
            $report['rule'] = $rule;
            $report['used_coupon'] = $useCouponable;

            $this->save($rule);

            return $report;
        } else {
            return false;
        }
    }

    public function checkApplicability($rule = null)
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

        $amountDiscounted = 0;
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
        $report['formatted_discount'] = core()->formatPrice($amountDiscounted, $cart->cart_currency_code);
        $report['new_grand_total'] = $cart->grand_total - $amountDiscounted;
        $report['formatted_new_grand_total'] = core()->formatPrice($cart->grand_total - $amountDiscounted, $cart->cart_currency_code);
        $report['priority'] = $rule->priority;

        return $report;
    }

    public function save($rule)
    {
        $cart = \Cart::getCart();

        // create or update
        $existingRule = $this->cartRuleCart->findWhere([
            'cart_id' => $cart->id
        ]);

        if (count($existingRule)) {
            if ($existingRule->first()->cart_rule_id != $rule->id) {
                $existingRule->first()->update([
                    'cart_rule_id' => $rule->id
                ]);

                return true;
            }
        } else {
            $this->cartRuleCart->create([
                'cart_id' => $cart->id,
                'cart_rule_id' => $rule->id
            ]);

            return true;
        }

        return false;
    }

    public function removeCoupon()
    {
        $cart = \Cart::getCart();

        $existingRule = $this->cartRuleCart->findWhere([
            'cart_id' => $cart->id
        ]);

        if ($existingRule->count()) {
            if ($existingRule->first()->delete()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
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