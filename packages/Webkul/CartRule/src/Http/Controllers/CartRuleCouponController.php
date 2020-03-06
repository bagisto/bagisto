<?php

namespace Webkul\CartRule\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;

class CartRuleCouponController extends Controller
{
    /**
     * To hold CartRuleCouponRepository repository instance
     * 
     * @var \Webkul\CartRule\Repositories\CartRuleCouponRepository
     */
    protected $cartRuleCouponRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\CartRule\Repositories\CartRuleCouponRepository  $cartRuleCouponRepository
     * @return void
     */
    public function __construct(CartRuleCouponRepository $cartRuleCouponRepository)
    {
        $this->cartRuleCouponRepository = $cartRuleCouponRepository;
    }

    /**
     * Mass Delete the coupons
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function massDelete()
    {
        $couponIds = explode(',', request()->input('indexes'));

        foreach ($couponIds as $couponId) {
            $coupon = $this->cartRuleCouponRepository->find($couponId);

            if ($coupon) {
                $this->cartRuleCouponRepository->delete($couponId);
            }
        }

        session()->flash('success', trans('admin::app.promotions.cart-rules.mass-delete-success'));

        return redirect()->back();
    }
}