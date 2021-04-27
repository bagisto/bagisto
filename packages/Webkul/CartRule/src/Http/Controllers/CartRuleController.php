<?php

namespace Webkul\CartRule\Http\Controllers;

use Exception;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Webkul\CartRule\Repositories\CartRuleRepository;
use Webkul\CartRule\Repositories\CartRuleCouponRepository;

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
     * @var \Webkul\CartRule\Repositories\CartRuleRepository
     */
    protected $cartRuleRepository;

    /**
     * To hold CartRuleCouponRepository repository instance
     *
     * @var \Webkul\CartRule\Repositories\CartRuleCouponRepository
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
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->_config['view']);
    }

    /**
     * Copy a given Cart Rule id. Always make the copy is inactive so the
     * user is able to configure it before setting it live.
     */
    public function copy(int $cartRuleId): View
    {
        $originalCartRule = $this->cartRuleRepository
            ->findOrFail($cartRuleId)
            ->load('channels')
            ->load('customer_groups');

        $copiedCartRule = $originalCartRule
            ->replicate()
            ->fill([
                'status' => 0,
                'name'   => __('admin::app.copy-of') . $originalCartRule->name,
            ]);

        $copiedCartRule->save();

        foreach ($copiedCartRule->channels as $channel) {
            $copiedCartRule->channels()->save($channel);
        }

        foreach ($copiedCartRule->customer_groups as $group) {
            $copiedCartRule->customer_groups()->save($group);
        }

        return view($this->_config['view'], [
            'cartRule' => $copiedCartRule,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        try {
            $this->validate(request(), [
                'name'                => 'required',
                'channels'            => 'required|array|min:1',
                'customer_groups'     => 'required|array|min:1',
                'coupon_type'         => 'required',
                'use_auto_generation' => 'required_if:coupon_type,==,1',
                'coupon_code'         => 'required_if:use_auto_generation,==,0|unique:cart_rule_coupons,code',
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
        } catch (ValidationException $e) {

            if ($firstError = collect($e->errors())->first()) {
                session()->flash('error', $firstError[0]);
            }
        }

        return redirect()->back();
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
        try {
            $this->validate(request(), [
                'name'                => 'required',
                'channels'            => 'required|array|min:1',
                'customer_groups'     => 'required|array|min:1',
                'coupon_type'         => 'required',
                'use_auto_generation' => 'required_if:coupon_type,==,1',
                'starts_from'         => 'nullable|date',
                'ends_till'           => 'nullable|date|after_or_equal:starts_from',
                'action_type'         => 'required',
                'discount_amount'     => 'required|numeric',
            ]);

            $cartRule = $this->cartRuleRepository->findOrFail($id);

            if ($cartRule->coupon_type) {
                if ($cartRule->cart_rule_coupon) {
                    $this->validate(request(), [
                        'coupon_code' => 'required_if:use_auto_generation,==,0|unique:cart_rule_coupons,code,' . $cartRule->cart_rule_coupon->id,
                    ]);
                } else {
                    $this->validate(request(), [
                        'coupon_code' => 'required_if:use_auto_generation,==,0|unique:cart_rule_coupons,code',
                    ]);
                }
            }

            Event::dispatch('promotions.cart_rule.update.before', $cartRule);

            $cartRule = $this->cartRuleRepository->update(request()->all(), $id);

            Event::dispatch('promotions.cart_rule.update.after', $cartRule);

            session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Cart Rule']));

            return redirect()->route($this->_config['redirect']);
        } catch (ValidationException $e) {

            if ($firstError = collect($e->errors())->first()) {
                session()->flash('error', $firstError[0]);
            }
        }

        return redirect()->back();
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
        } catch (Exception $e) {
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

        if (! request('id')) {
            return response()->json(['message' => trans('admin::app.promotions.cart-rules.cart-rule-not-defind-error')], 400);
        }

        $this->cartRuleCouponRepository->generateCoupons(request()->all(), request('id'));

        return response()->json(['message' => trans('admin::app.response.create-success', ['name' => 'Cart rule coupons'])]);
    }
}