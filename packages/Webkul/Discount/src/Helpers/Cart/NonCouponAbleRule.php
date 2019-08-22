<?php

namespace Webkul\Discount\Helpers\Cart;

use Webkul\Discount\Helpers\Cart\Discount;

class NonCouponAbleRule extends Discount
{
    /**
     * Applies the non couponable rule on the current cart instance
     *
     * @param String $code
     *
     * @return mixed
     */
    public function apply($code = null)
    {
        $this->validateIfAlreadyApplied();

        $rules = $this->getApplicableRules();

        if ($rules->count() == 1) {
            $rule = $rules->first();

            $canApply = $this->canApply($rule);

            if ($canApply) {
                $this->save($rule);

                $this->updateCartItemAndCart($rule);
            }
        } else if ($rules->count() > 1) {
            $rule = $this->breakTie($rules);

            $canApply = $this->canApply($rule);

            if ($canApply) {
                $this->save($rule);

                $this->updateCartItemAndCart($rule);
            }
        } else {
            return false;
        }
    }
}