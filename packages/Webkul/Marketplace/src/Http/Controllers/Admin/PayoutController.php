<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Webkul\Marketplace\Enums\PayoutStatus;
use Webkul\Marketplace\Repositories\PayoutRepository;

class PayoutController extends Controller
{
    public function __construct(protected PayoutRepository $payoutRepository) {}

    public function index(): View
    {
        $payouts = $this->payoutRepository->with('seller')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('marketplace::admin.payouts.index', compact('payouts'));
    }

    public function approve(int $id): RedirectResponse
    {
        $payout = $this->payoutRepository->findOrFail($id);
        $payout->markAsPaid(request('transaction_id', ''));

        session()->flash('success', trans('marketplace::app.admin.payouts.approve-success'));

        return redirect()->route('admin.marketplace.payouts.index');
    }

    public function reject(int $id): RedirectResponse
    {
        $payout = $this->payoutRepository->findOrFail($id);
        $payout->update([
            'status' => PayoutStatus::Rejected,
            'notes'  => request('notes'),
        ]);

        session()->flash('error', trans('marketplace::app.admin.payouts.reject-success'));

        return redirect()->route('admin.marketplace.payouts.index');
    }
}
