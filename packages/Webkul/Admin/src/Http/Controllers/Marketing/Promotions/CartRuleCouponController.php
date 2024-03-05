<?php

namespace Webkul\Admin\Http\Controllers\Marketing\Promotions;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\DataGrids\Marketing\Promotions\CartRuleCouponDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;

class CartRuleCouponController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CartRuleCouponRepository $cartRuleCouponRepository)
    {
    }

    /**
     * Index.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $id)
    {
        return app(CartRuleCouponDataGrid::class)->toJson();
    }

    /**
     * Generate coupon code for cart rule.
     */
    public function store(int $id): JsonResponse
    {
        $this->validate(request(), [
            'coupon_qty'  => 'required|integer|min:1',
            'code_length' => 'required|integer|min:10',
            'code_format' => 'required',
        ]);

        if (! $id) {
            return new JsonResponse([
                'message' => trans('admin::app.promotions.cart-rules-coupons.cart-rule-not-defined-error'),
            ], 400);
        }

        $this->cartRuleCouponRepository->generateCoupons(request()->only(
            'coupon_qty',
            'code_length',
            'code_format',
            'code_prefix',
            'code_suffix'
        ), $id);

        return new JsonResponse([
            'message' => trans(
                'admin::app.marketing.promotions.cart-rules-coupons.success', ['name' => 'Cart rule coupons']
            ),
        ]);
    }

    /**
     * Delete Generated coupon code
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->cartRuleCouponRepository->delete($id);

            return new JsonResponse([
                'message' => trans('admin::app.marketing.promotions.cart-rules-coupons.delete-success'),
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => trans('admin::app.marketing.promotions.cart-rules-coupons.cart-rule-not-defined-error'),
            ], 400);
        }
    }

    /**
     * Mass delete the coupons.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDelete(MassDestroyRequest $massDestroyRequest)
    {
        $couponIds = $massDestroyRequest->input('indices');

        foreach ($couponIds as $couponId) {
            $coupon = $this->cartRuleCouponRepository->find($couponId);

            if ($coupon) {
                $this->cartRuleCouponRepository->delete($couponId);
            }
        }

        session()->flash('success', trans('admin::app.marketing.promotions.cart-rules-coupons.mass-delete-success'));

        return redirect()->back();
    }
}
