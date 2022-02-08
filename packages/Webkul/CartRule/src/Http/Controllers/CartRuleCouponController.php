<?php

namespace Webkul\CartRule\Http\Controllers;

use Webkul\Admin\DataGrids\CartRuleCouponDataGrid;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;

class CartRuleCouponController extends Controller
{
    /**
     * To hold cart rule coupon repository instance.
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
     * Index.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return app(CartRuleCouponDataGrid::class)->toJson();
    }

    /**
     * Generate coupon code for cart rule.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id)
    {
        $this->validate(request(), [
            'coupon_qty'  => 'required|integer|min:1',
            'code_length' => 'required|integer|min:10',
            'code_format' => 'required',
        ]);

        if (! request('id')) {
            return response()->json(['message' => trans('admin::app.promotions.cart-rules.cart-rule-not-defind-error')], 400);
        }

        $this->cartRuleCouponRepository->generateCoupons(request()->all(), request('id'));

        return response()->json(['message' => trans('admin::app.response.create-success', ['name' => 'Cart rule coupons'])]);
    }

    /**
     * Mass delete the coupons.
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
