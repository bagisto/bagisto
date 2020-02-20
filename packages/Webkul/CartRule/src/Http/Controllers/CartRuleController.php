<?php

namespace Webkul\CartRule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Webkul\CartRule\Repositories\CartRuleRepository;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;

/**
 * Cart Rule controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CartRuleController extends Controller
{
    /**
     * Initialize _config, a default request parameter with route
     * 
     * @param array
     */
    protected $_config;

    /**
     * To hold Cart repository instance
     * 
     * @var CartRuleRepository
     */
    protected $cartRuleRepository;

    /**
     * To hold CartRuleCouponRepository repository instance
     * 
     * @var CartRuleCouponRepository
     */
    protected $cartRuleCouponRepository;

    /**
     * Create a new controller instance.
     *
     * @param \Webkul\CartRule\Repositories\CartRuleRepository       $cartRuleRepository
     * @param \Webkul\CartRule\Repositories\CartRuleCouponRepository $cartRuleCouponRepository
     *
     * @return void
     */
    public function __construct(
        CartRuleRepository $cartRuleRepository,
        CartRuleCouponRepository $cartRuleCouponRepository
    )
    {
        $this->_config = request('_config');

        $this->cartRuleRepository = $cartRuleRepository;

        $this->cartRuleCouponRepository = $cartRuleCouponRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name'                => 'required',
            'channels'            => 'required|array|min:1',
            'customer_groups'     => 'required|array|min:1',
            'coupon_type'         => 'required',
            'use_auto_generation' => 'required_if:coupon_type,==,1',
            'coupon_code'         => 'required_if:use_auto_generation,==,0',
            'starts_from'         => 'nullable|date',
            'ends_till'           => 'nullable|date|after_or_equal:starts_from',
            'action_type'         => 'required',
            'discount_amount'     => 'required|numeric',
        ]);

        $data = request()->all();

        Event::dispatch('promotions.cart_rule.create.before');

        $cartRule = $this->cartRuleRepository->create($data);

        Event::dispatch('promotions.cart_rule.create.after', $cartRule);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => 'Cart Rule']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cartRule = $this->cartRuleRepository->findOrFail($id);

        return view($this->_config['view'], compact('cartRule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'name'                => 'required',
            'channels'            => 'required|array|min:1',
            'customer_groups'     => 'required|array|min:1',
            'coupon_type'         => 'required',
            'use_auto_generation' => 'required_if:coupon_type,==,1',
            'coupon_code'         => 'required_if:use_auto_generation,==,0',
            'starts_from'         => 'nullable|date',
            'ends_till'           => 'nullable|date|after_or_equal:starts_from',
            'action_type'         => 'required',
            'discount_amount'     => 'required|numeric',
        ]);

        $cartRule = $this->cartRuleRepository->findOrFail($id);

        Event::dispatch('promotions.cart_rule.update.before', $cartRule);

        $cartRule = $this->cartRuleRepository->update(request()->all(), $id);

        Event::dispatch('promotions.cart_rule.update.after', $cartRule);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Cart Rule']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cartRule = $this->cartRuleRepository->findOrFail($id);

        try {
            Event::dispatch('promotions.cart_rule.delete.before', $id);

            $this->cartRuleRepository->delete($id);

            Event::dispatch('promotions.cart_rule.delete.after', $id);

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Cart Rule']));

            return response()->json(['message' => true], 200);
        } catch (\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => 'Cart Rule']));
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * Generate coupon code for cart rule
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateCoupons()
    {
        $this->validate(request(), [
            'coupon_qty'  => 'required|integer|min:1',
            'code_length' => 'required|integer|min:10',
            'code_format' => 'required',
        ]);
        
        if (! request('id'))
            return response()->json(['message' => trans('admin::app.promotions.cart-rules.cart-rule-not-defind-error')], 400);

        $this->cartRuleCouponRepository->generateCoupons(request()->all(), request('id'));

        return response()->json(['message' => trans('admin::app.response.create-success', ['name' => 'Cart rule coupons'])]);
    }
}