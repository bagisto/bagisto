<?php

namespace Webkul\Checkout\Traits;

/**
 * Cart coupons. In this trait, you will get all sorted collections of
 * methods which can be use for coupon in cart.
 *
 * Note: This trait will only work with the Cart facade. Unless and until,
 * you have all the required repositories in the parent class.
 */
trait CartCoupons
{
    /**
     * Set coupon code to the cart.
     *
     * @param  string  $code
     * @return \Webkul\Checkout\Contracts\Cart
     */
    public function setCouponCode($code)
    {
        $cart = $this->getCart();

        $cart->coupon_code = $code;

        $cart->save();

        return $this;
    }

    /**
     * Remove coupon code from the cart.
     *
     * @return \Webkul\Checkout\Contracts\Cart
     */
    public function removeCouponCode()
    {
        $cart = $this->getCart();

        $cart->coupon_code = null;

        $cart->save();

        return $this;
    }
}