<?php

namespace Webkul\CartRule\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Webkul\Admin\DataGrids\CartRuleDataGrid;
use Webkul\CartRule\Http\Requests\CartRuleRequest;
use Webkul\CartRule\Repositories\CartRuleRepository;

class CartRuleController extends Controller
{
    /**
     * Initialize _config, a default request parameter with route.
     *
     * @param array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\CartRule\Repositories\CartRuleRepository  $cartRuleRepository
     * @return void
     */
    public function __construct(protected CartRuleRepository $cartRuleRepository)
    {
        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(CartRuleDataGrid::class)->toJson();
        }

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
     * Copy a given Cart Rule id. Always make the copy is inactive so the
     * user is able to configure it before setting it live.
     *
     * @param  int  $cartRuleId
     * @return \Illuminate\View\View
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
     * @param  \Webkul\CartRule\Http\Requests\CartRuleRequest  $cartRuleRequest
     * @return \Illuminate\Http\Response
     */
    public function store(CartRuleRequest $cartRuleRequest)
    {
        try {
            Event::dispatch('promotions.cart_rule.create.before');

            $cartRule = $this->cartRuleRepository->create($cartRuleRequest->all());

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
     * @param  int  $id
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
     * @param  \Webkul\CartRule\Http\Requests\CartRuleRequest  $cartRuleRequest
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CartRuleRequest $cartRuleRequest, $id)
    {
        try {
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

            Event::dispatch('promotions.cart_rule.update.before', $id);

            $cartRule = $this->cartRuleRepository->update($cartRuleRequest->all(), $id);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->cartRuleRepository->findOrFail($id);

        try {
            Event::dispatch('promotions.cart_rule.delete.before', $id);

            $this->cartRuleRepository->delete($id);

            Event::dispatch('promotions.cart_rule.delete.after', $id);

            return response()->json(['message' => trans('admin::app.response.delete-success', ['name' => 'Cart Rule'])]);
        } catch (Exception $e) {}

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Cart Rule'])], 400);
    }
}
