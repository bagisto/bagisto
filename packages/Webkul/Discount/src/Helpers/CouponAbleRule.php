<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Helpers\Discount;

use Carbon\Carbon;
use Cart;

class CouponAbleRule extends Discount
{
    /**
     * Applies the couponable rule on the current cart instance
     *
     * @return mixed
     */
    public function apply($code)
    {
        $cart = Cart::getCart();

        if (auth()->guard('customer')->check()) {
            $rules = $this->cartRule->findWhere([
                'use_coupon' => 1,
                'status' => 1
            ]);
        } else {
            $rules = $this->cartRule->findWhere([
                'use_coupon' => 1,
                'is_guest' => 1,
                'status' => 1
            ]);
        }

        $applicableRule = null;

        foreach($rules as $rule) {
            if ($rule->use_coupon && ($rule->coupons->code == $code)) {
                $applicableRule = $rule;

                break;
            }
        }

        $applicability = $this->checkApplicability($applicableRule);

        if ($applicability) {
            $item = $this->leastWorthItem();

            $actionInstance = new $this->rules[$rule->action_type];

            $impact = $actionInstance->calculate($applicableRule, $item, $cart);

            $ifAlreadyApplied = $this->cartRuleCart->findWhere([
                'cart_id' => $cart->id,
                'cart_rule_id' => $rule->id
            ]);

            if ($ifAlreadyApplied->count() == 1) {
                return false;
            }

            $ifAlreadyApplied = $this->cartRuleCart->findWhere([
                'cart_id' => $cart->id,
            ]);

            if ($ifAlreadyApplied->count() == 0) {
                $this->save($applicableRule);

                return $impact;
            }

            $alreadyAppliedRule = $ifAlreadyApplied->first()->cart_rule;

            if ($alreadyAppliedRule->priority < $rule->priority) {
                return false;
            } else if ($alreadyAppliedRule->priority == $applicableRule->priority) {
                // tie breaker case

                // end other rules
                if ($alreadyAppliedRule->end_other_rules) {
                    return false;
                }

                $actionInstance = new $this->rules[$alreadyAppliedRule->action_type];

                $alreadyAppliedRuleImpact = $actionInstance->calculate($alreadyAppliedRule, $item, $cart);

                if ($alreadyAppliedRule['discount'] > $impact['discount']) {
                    return false;
                } else if ($alreadyAppliedRule['discount'] < $impact['discount']) {
                    $this->save($applicableRule);

                    return $impact;
                } else {
                    // least id case
                    if ($applicableRule->id < $alreadyAppliedRule->id) {
                        return $impact;
                    }
                }
            } else {
                $this->save($applicableRule);

                return $impact;
            }
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
            if ($existingRule->first()->delete()) {
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
        } else {
            return false;
        }
    }
}