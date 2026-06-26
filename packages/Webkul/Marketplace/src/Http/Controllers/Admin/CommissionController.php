<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Webkul\Marketplace\Repositories\MarketplaceOrderRepository;

class CommissionController extends Controller
{
    public function __construct(protected MarketplaceOrderRepository $marketplaceOrderRepository) {}

    public function index(): View
    {
        $commissions = $this->marketplaceOrderRepository->with(['seller', 'order'])
            ->paginate(25);

        return view('marketplace::admin.commissions.index', compact('commissions'));
    }

    public function markPaid(int $id): RedirectResponse
    {
        $commission = $this->marketplaceOrderRepository->findOrFail($id);
        $commission->markAsPaid();

        session()->flash('success', trans('marketplace::app.admin.commissions.paid-success'));

        return redirect()->route('admin.marketplace.commissions.index');
    }
}
