<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Helpers\Discount;

class ValidatesDiscount extends Discount
{
    /**
     * Validates the currently applied cart rule on the current cart
     */
    public function validate($cart)
    {
        $appliedRule = $this->cartRuleCart->findWhere([
            'cart_id' => $cart->id
        ]);

        if ($appliedRule->count()) {
            $appliedRule = $appliedRule->first()->cart_rule;

            $applicability = $this->checkApplicability($appliedRule);

            dd($applicability);
        }
    }
}