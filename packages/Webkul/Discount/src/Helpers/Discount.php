<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Repositories\CartRuleRepository as CartRule;
use Webkul\Discount\Repositories\CartRuleCartRepository as CartRuleCart;
use Carbon\Carbon;
use Arr;

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
        $cart = \Cart::getCart();

        $previousRule = $this->cartRuleCart->findWhere([
            'cart_id' => $cart->id
        ]);

        if ($previousRule->count()) {
            $previousRule = $previousRule->first()->cart_rule;

            if ($previousRule->use_coupon) {

                return 'false';
            }
        } else {
            \Cart::clearDiscount();
        }

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

                        foreach (\Cart::getCart()->items as $item) {
                            if ($item->id == $itemId['item_id']) {
                                $item->update([
                                    'discount_amount' => array_first($canBeApplied)['discount'],
                                    'base_discount_amount' => array_first($canBeApplied)['discount']
                                ]);

                                break;
                            }
                        }

                        $this->save($rule['rule']);

                        return $rule;
                    }
                }
            }
        }

        if ($canBeApplied->count()) {
            $itemId = array_first($canBeApplied);

            foreach (\Cart::getCart()->items as $item) {
                if ($item->id == $itemId['item_id']) {
                    $item->update([
                        'discount_amount' => array_first($canBeApplied)['discount'],
                        'base_discount_amount' => array_first($canBeApplied)['discount']
                    ]);

                    break;
                }
            }

            $this->save(array_first($canBeApplied)['rule']);

            return array_first($canBeApplied);
        } else {
            return 'false';
        }
    }

    public function applyCouponAbleRule($code)
    {
        $cart = \Cart::getCart();

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
                    if ($alreadyAppliedRule->id != $rule->id) {
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
                                if (array_first($canBeApplied)['rule']->use_coupon) {
                                    $useCouponable = true;
                                }
                            }
                        } else if (count($canBeApplied)) {
                            if (array_first($canBeApplied)['rule']->use_coupon) {
                                $useCouponable = true;
                            }
                        }

                        if ($alreadyAppliedRule->end_other_rules) {
                            $useCouponable = false;
                        }
                    } else {
                        $report = $this->checkApplicability($rule);

                        array_push($canBeApplied, $report);
                    }
                }
            }
        }

        if ($useCouponable) {
            $report = $this->checkApplicability($rule);
            $report['rule'] = $rule;
            $report['used_coupon'] = $useCouponable;

            $itemId = array_first($canBeApplied);

            foreach ($cart->items as $item) {
                if ($item->id == $itemId['item_id']) {
                    $item->update([
                        'discount_amount' => array_first($canBeApplied)['discount'],
                        'base_discount_amount' => array_first($canBeApplied)['discount'],
                        'coupon_code' => $rule->coupons->code
                    ]);

                    $cart->update([
                        'coupon_code' => $rule->coupons->code
                    ]);

                    \Cart::collectTotals();

                    break;
                }
            }

            // saves the rule in cart rule cart
            $this->save($rule);

            return $report;
        } else {
            return null;
        }
    }

    /**
     * This function checks whether the rule is getting applied on the current cart or noy
     *
     * @return mixed
     */
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

                if ($amountDiscounted > $leastWorthItem['total']) {
                    $amountDiscounted = $leastWorthItem['total'];
                }
            } else if ($action_type == config('pricerules.cart.validation.1')) {
                $amountDiscounted = $disc_amount;

                if ($amountDiscounted > $leastWorthItem['total']) {
                    $amountDiscounted = $leastWorthItem['total'];
                }
            } else if ($action_type == config('pricerules.cart.validation.2')) {
                $amountDiscounted = $disc_amount;

                if ($amountDiscounted > $leastWorthItem['total']) {
                    $amountDiscounted = $leastWorthItem['total'];
                }
            }
        }

        $report['item_id'] = $leastWorthItem['id'];
        $report['item_price'] = $leastWorthItem['total'];
        $report['discount'] = $amountDiscounted;
        $report['action'] = $action_type;
        $report['formatted_discount'] = core()->formatPrice($amountDiscounted, $cart->cart_currency_code);
        $report['new_grand_total'] = $cart->grand_total - $amountDiscounted;
        $report['formatted_new_grand_total'] = core()->formatPrice($cart->grand_total - $amountDiscounted, $cart->cart_currency_code);
        $report['priority'] = $rule->priority;

        return $report;
    }

    /**
     * Save the rule in the cart rule cart
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

    /**
     * Removes the cart rule from the cart
     */
    public function removeRule()
    {
        $cart = Cart::getCart();

        $appliedRule = $this->cartRuleCart->findWhere([
            'cart_id' => $cart->id
        ]);

        if ($appliedRule->count() == 0) {
            Cart::clearDiscount();

            Cart::collectTotals();
        }

        return true;
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
        $shipping_address = $cart->getShippingAddressAttribute() ?? '';

        $shipping_method = $cart->shipping_method ?? '';
        $shipping_country = $shipping_address->country ?? '';
        $shipping_state = $shipping_address->state ?? '';
        $shipping_postcode = $shipping_address->postcode ?? '';
        $shipping_city = $shipping_address->city ?? '';

        $payment_method = $cart->payment->method ?? '';
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