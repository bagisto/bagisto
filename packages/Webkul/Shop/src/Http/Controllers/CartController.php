<?php

namespace Webkul\Shop\Http\Controllers;

use Cart;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CartRuleCouponRepository $cartRuleCouponRepository
    ) {}

    /**
     * Cart page.
     *
     * @return \Illuminate\View\View
     */
     public function index()
     {
        return view('shop::checkout.cart.index');
     }

    /**
     * Apply coupon to the cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeCoupon()
    {
        $couponCode = request()->get('code');

        try {
            if (strlen($couponCode)) {
                $coupon = $this->cartRuleCouponRepository->findOneByField('code', $couponCode);

                if ($coupon->cart_rule->status) {
                    if (Cart::getCart()->coupon_code == $couponCode) {
                        return redirect()->back();
                    }

                    Cart::setCouponCode($couponCode)->collectTotals();

                    if (Cart::getCart()->coupon_code == $couponCode) {
                        return redirect()->back();
                    }
                }
            }

            return redirect()->back();
        } catch (\Exception $e) {
            report($e);

            return redirect()->back();
        }
    }

    /**
     * Remove applied coupon from the cart.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroyCoupon()
    {
        Cart::removeCouponCode()->collectTotals();

        return redirect()->back();
    }
}
