<?php

namespace Webkul\Shop\Http\Controllers;

use Cart;
use Illuminate\Support\Facades\Event;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Customer\Repositories\WishlistRepository;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected CartRuleCouponRepository $cartRuleCouponRepository,
        protected ProductRepository $productRepository,
        protected WishlistRepository $wishlistRepository,
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
                        session()->flash('success', trans('shop::app.checkout.cart.coupon-already-applied'));

                        return redirect()->back();
                    }

                    Cart::setCouponCode($couponCode)->collectTotals();

                    if (Cart::getCart()->coupon_code == $couponCode) {
                        session()->flash('success', trans('shop::app.checkout.cart.coupon.success-apply'));

                        return redirect()->back();
                    }
                }
            }

            session()->flash('danger', trans('shop::app.checkout.cart.coupon.invalid'));

            return redirect()->back();

        } catch (\Exception $e) {
            report($e);

            session()->flash('warning', trans('shop::app.checkout.cart.coupon.apply-issue'));

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

        session()->flash('warning', trans('shop::app.checkout.cart.coupon.remove'));

        return redirect()->back();
    }
}
