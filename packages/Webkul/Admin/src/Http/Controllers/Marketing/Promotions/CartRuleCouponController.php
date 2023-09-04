<?php

namespace Webkul\Admin\Http\Controllers\Marketing\Promotions;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Http\Requests\MassDestroyRequest;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;
use Webkul\Admin\DataGrids\Marketing\Promotions\CartRuleCouponDataGrid;

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
     * @param int $id
     * @return JsonResource
     */
    public function store($id):JsonResource
    {
        $this->validate(request(), [
            'coupon_qty'  => 'required|integer|min:1',
            'code_length' => 'required|integer|min:10',
            'code_format' => 'required',
        ]);

        if (!request('id')) {
            return new JsonResource([
                'message' => trans('admin::app.promotions.cart-rules-coupons.cart-rule-not-defined-error'
            )], 400);
        }

        $this->cartRuleCouponRepository->generateCoupons(request()->all(), request('id'));

        return new JsonResource([
            'message' => trans('admin::app.promotions.cart-rules-coupons.success', ['name' => 'Cart rule coupons']
        )]);
    }

    /**
     * Mass delete the coupons.
     *
     * @param  int  $id
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

        session()->flash('success', trans('admin::app.promotions.cart-rules-coupons.mass-delete-success'));

        return redirect()->back();
    }
}
