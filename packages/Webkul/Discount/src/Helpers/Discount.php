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

    public function checkCoupon()
    {
        foreach($this->cartRule->all() as $rule) {
            return $rule->name;
        }
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
        $endRules = $this->getEndRules();

        if (isset($endRules)) {
            return [
                'end_rule' => true,
                'rule' => $endRules
            ];
        } else {
            $bestRules = $this->getBestRules();

            return [
                'end_rule' => false,
                'rule' => $bestRules
            ];
        }
    }

    public function isCouponAble($rule)
    {
        if ($rule->use_coupon) {
            return true;
        } else {
            return false;
        }
    }
}