<?php

namespace Webkul\Discount\Helpers\Cart;

use Webkul\Discount\Helpers\Cart\Discount;
use Cart;

class CouponAbleRule extends Discount
{
    /**
     * Applies the non couponable rule on the current cart instance
     *
     * @param String $code
     *
     * @return mixed
     */
    public function apply($code)
    {
        $this->validateIfAlreadyApplied();

        $rules = $this->getApplicableRules($code);

        if ($rules->count() == 1) {
            $rule = $rules->first();

            $canApply = $this->canApply($rule);

            if ($canApply) {
                $this->save($rule);

                $this->updateCartItemAndCart($rule);

                return true;
            }
        } else {
            return false;
        }

        return false;
    }

    /**
     * Removes the already applied coupon on the current cart instance
     *
     * @return boolean
     */
    public function remove()
    {
        $cart = Cart::getCart();

        $existingRule = $this->cartRuleCart->findWhere(['cart_id' => $cart->id]);

        $this->clearDiscount();

        return true;
    }
}