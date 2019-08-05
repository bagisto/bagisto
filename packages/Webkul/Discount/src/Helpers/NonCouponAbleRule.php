<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Helpers\Discount;

use Cart;

class NonCouponAbleRule extends Discount
{
    /**
     * Applies the non couponable rule on the current cart instance
     *
     * @return mixed
     */
    public function apply($code = null)
    {
        $rules = $this->getApplicableRules();

        if ($rules->count() == 1) {
            $rule = $rules->first();

            $this->save($rule);
        } else if ($rules->count() > 1) {
            $rule = $this->breakTie($rules);

            $this->save($rule);
        } else {
            return $rules;
        }
    }
}