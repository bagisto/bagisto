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
        $rules = $this->cartRule->findWhere(['status' => 1, 'is_guest' => 1]);
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
                    'noncouponable' => true,
                    'id' => $id
                ];
            } else {
                return [
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
                    'noncouponable' => true,
                    'id' => $id
                ];
            } else {
                return [
                    'noncouponable' => false,
                    'id' => null
                ];
            }
        }
    }

    public function checkCouponConditions($cart)
    {
        $rules = $this->applyCouponAble();

        return $rules;
        //non or null condtion based

        // condition based rules

    }

    public function checkNonCouponConditions($cart)
    {
        $rules = $this->applyNonCouponAble();

        return $rules;
        foreach($rules['id'] as $rule) {
            $action_type = $rule->action_type;
            $disc_amount = $rule->disc_amount;
            $disc_quantity = $rule->disc_quantity;
            $disc_threshold = $rule->disc_threshold;

            if($rule->conditions == null) {
                // non or null condition based
                if ($cart->items_qty >= $disc_threshold) {
                    if ($action_type == config('pricerules.cart.validations.0')) {
                        $itemBaseSubTotal = $cart->base_sub_total * ($disc_amount / 100);
                        //CART
                        // update disc amount

                        //CART Item
                        //update disc percentage
                        //update disc amount
                        //update base disc amount
                    } else if ($action_type == config('pricerules.cart.validations.1')) {
                        $baseSubTotal = $disc_amount;
                        //CART
                        // update disc amount

                        //CART Item
                        //update disc amount
                        //update base disc amount
                    } else if ($action_type == config('pricerules.cart.validations.2')) {
                        //CART
                        $quantity = $cart->items()->first()->quantity + 1;
                        // update disc amount

                        //CART Item
                        //update disc percentage
                        //update disc amount
                        //update base disc amount

                    } else if ($action_type == config('pricerules.cart.validations.3')) {
                        //CART
                        $cartBaseSubTotal = $disc_amount ;
                        // update disc amount

                        //CART Item
                        //update disc percentage
                        //update disc amount
                        //update base disc amount
                    }
                }
            } else {
                // condition based rules
                dd('conditions found');

            }
        }

    }
}