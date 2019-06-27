<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Helpers\Discount;
use Webkul\Discount\Repositories\CartRuleCartRepository as CartRuleCart;

class ValidatesDiscount extends Discount
{
    /**
     * CartRuleCartRepository instance
     */
    protected $cartRuleCart;

    /**
     * Initializes type hinted dependencies
     *
     * @param CartRuleCart $cartRuleCart
     */
    public function __construct(CartRuleCart $cartRuleCart)
    {
        $this->cartRuleCart = $cartRuleCart;
    }

    public function apply($code)
    {
        return ;
    }

    /**
     * Validates the currently applied cart rule on the current cart
     *
     * @param $cart instance
     *
     * @return mixed
     */
    public function validate($cart)
    {
        $appliedRule = $this->cartRuleCart->findWhere([
            'cart_id' => $cart->id
        ]);

        if ($appliedRule->count()) {
            $appliedRule = $appliedRule->first()->cart_rule;

            if ($appliedRule->status == 1) {
                $applicability = $this->checkApplicability($appliedRule);

                if (! $applicability) {
                    return $this->remove();
                } else {
                    if ($appliedRule->free_shipping && $cart->selected_shipping_rate->base_price > 0) {
                        $cart->selected_shipping_rate->update([
                            'price' => 0,
                            'base_price' => 0
                        ]);
                    } else if ($appliedRule->free_shipping == 0 && $appliedRule->apply_to_shipping && $cart->selected_shipping_rate->base_price > 0) {
                        $actionType = config('discount-rules')[$appliedRule->action_type];

                        if ($appliedRule->apply_to_shipping) {
                            $actionInstance = new $actionType;

                            $discountOnShipping = $actionInstance->calculateOnShipping($cart);

                            $cart->selected_shipping_rate->update([
                                'price' => $cart->selected_shipping_rate->base_price - $discountOnShipping,
                                'base_price' => $cart->selected_shipping_rate->price - core()->convertPrice($discountOnShipping, $cart->cart_currency_code)
                            ]);
                        }
                    }
                }
            } else {
                return $this->remove();
            }
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

        $existingRule = $this->cartRuleCart->findWhere([
            'cart_id' => $cart->id
        ]);

        if ($existingRule->count()) {
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
    }
}