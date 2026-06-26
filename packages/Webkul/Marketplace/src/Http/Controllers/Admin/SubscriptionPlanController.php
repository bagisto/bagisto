<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Webkul\Marketplace\Repositories\SubscriptionPlanRepository;

class SubscriptionPlanController extends Controller
{
    public function __construct(protected SubscriptionPlanRepository $planRepository) {}

    public function index(): View
    {
        $plans = $this->planRepository->orderBy('sort_order')->get();

        return view('marketplace::admin.subscriptions.index', compact('plans'));
    }

    public function create(): View
    {
        return view('marketplace::admin.subscriptions.create');
    }

    public function store(): RedirectResponse
    {
        request()->validate([
            'name'            => 'required|string|max:191',
            'slug'            => 'required|string|max:191|unique:marketplace_subscription_plans,slug',
            'price'           => 'required|numeric|min:0',
            'interval'        => 'required|in:monthly,yearly',
            'commission_rate' => 'required|numeric|min:0|max:100',
            'max_products'    => 'nullable|integer|min:0',
        ]);

        $this->planRepository->create(request()->only([
            'name', 'slug', 'price', 'interval', 'commission_rate',
            'max_products', 'description', 'stripe_price_id', 'is_active',
        ]));

        session()->flash('success', trans('marketplace::app.admin.subscriptions.create-success'));

        return redirect()->route('admin.marketplace.subscriptions.index');
    }

    public function edit(int $id): View
    {
        $plan = $this->planRepository->findOrFail($id);

        return view('marketplace::admin.subscriptions.edit', compact('plan'));
    }

    public function update(int $id): RedirectResponse
    {
        request()->validate([
            'name'            => 'required|string|max:191',
            'price'           => 'required|numeric|min:0',
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $this->planRepository->update(
            request()->only(['name', 'price', 'interval', 'commission_rate', 'max_products', 'description', 'stripe_price_id', 'is_active']),
            $id
        );

        session()->flash('success', trans('marketplace::app.admin.subscriptions.update-success'));

        return redirect()->route('admin.marketplace.subscriptions.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->planRepository->delete($id);

        session()->flash('success', trans('marketplace::app.admin.subscriptions.delete-success'));

        return redirect()->route('admin.marketplace.subscriptions.index');
    }
}
