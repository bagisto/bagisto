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

        $rules = $this->cartRule->findWhere([
            'use_coupon' => 1,
            'status' => 1
        ]);

        $applicableRule = null;

        foreach($rules as $rule) {
            if ($rule->use_coupon && ($rule->coupons->code == $code)) {
                $applicableRule = $rule;

                break;
            }
        }

        if (! isset($applicableRule)) {
            return false;
        }

        $applicability = $this->checkApplicability($applicableRule);

        if ($applicability) {
            $item = $this->leastWorthItem();

            $actionInstance = new $this->rules[$applicableRule->action_type];

            $impact = $actionInstance->calculate($applicableRule, $item, $cart);

            // avoid applying the same rule
            $ifAlreadyApplied = $this->cartRuleCart->findWhere([
                'cart_id' => $cart->id,
                'cart_rule_id' => $applicableRule->id
            ]);

            if ($ifAlreadyApplied->count() == 1) {
                return false;
            }

            // if the rule ain't same
            $ifAlreadyApplied = $this->cartRuleCart->findWhere([
                'cart_id' => $cart->id,
            ]);

            if ($ifAlreadyApplied->count() == 0) {
                $this->save($applicableRule);

                return $impact;
            }

            if ($ifAlreadyApplied->first()->cart_rule->use_coupon == 1 && $ifAlreadyApplied->first()->cart_rule->end_other_rules == 0) {
                $alreadyAppliedRule = $ifAlreadyApplied->first()->cart_rule;

                if ($alreadyAppliedRule->priority < $applicableRule->priority) {
                    return false;
                } else if ($alreadyAppliedRule->priority == $applicableRule->priority) {
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
                            $this->save($applicableRule);

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
            if ($existingRule->first()->cart_rule->use_coupon) {
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
        } else {
            return false;
        }
    }
}