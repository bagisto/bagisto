<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Checkout\Facades\Cart;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Customer\Repositories\WishlistRepository;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\CartRule\Repositories\CartRuleCouponRepository  $cartRuleCouponRepository
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Customer\Repositories\WishlistRepository  $wishlistRepository
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
     */
    public function storeCoupon(): JsonResource
    {
        $couponCode = request()->input('code');

        try {
            if (strlen($couponCode)) {
                $coupon = $this->cartRuleCouponRepository->findOneByField('code', $couponCode);

                if ($coupon->cart_rule->status) {
                    if (Cart::getCart()->coupon_code == $couponCode) {
                        return new JsonResource([
                            'message'  => trans('shop::app.checkout.cart.coupon-already-applied'),
                        ]);
                    }

                    Cart::setCouponCode($couponCode)->collectTotals();

                    if (Cart::getCart()->coupon_code == $couponCode) {
                        return new JsonResource([
                            'message'  => trans('shop::app.checkout.cart.coupon.success-apply'),
                        ]);
                    }
                }
            }

            return new JsonResource([
                'message'  => trans('shop::app.checkout.cart.coupon-already-applied'),
            ]);
        } catch (\Exception $e) {
            return new JsonResource([
                'message'  => trans('shop::app.checkout.cart.coupon.success-apply'),
            ]);
        }
    }

    /**
     * Remove applied coupon from the cart.
     */
    public function destroyCoupon(): JsonResource
    {
        Cart::removeCouponCode()->collectTotals();

        return new JsonResource([
            'message'  => trans('shop::app.checkout.cart.coupon.remove'),
        ]);
    }
}
