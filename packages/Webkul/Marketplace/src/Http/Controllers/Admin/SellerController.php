<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Webkul\Marketplace\Repositories\SellerRepository;

class SellerController extends Controller
{
    public function __construct(protected SellerRepository $sellerRepository) {}

    public function index(): View
    {
        $sellers = $this->sellerRepository->paginate(25);

        return view('marketplace::admin.sellers.index', compact('sellers'));
    }

    public function view(int $id): View
    {
        $seller = $this->sellerRepository->findOrFail($id);

        return view('marketplace::admin.sellers.view', compact('seller'));
    }

    public function approve(int $id): RedirectResponse
    {
        $this->sellerRepository->approve($id);

        session()->flash('success', trans('marketplace::app.admin.sellers.approve-success'));

        return redirect()->route('admin.marketplace.sellers.view', $id);
    }

    public function suspend(int $id): RedirectResponse
    {
        $this->sellerRepository->suspend($id);

        session()->flash('success', trans('marketplace::app.admin.sellers.suspend-success'));

        return redirect()->route('admin.marketplace.sellers.view', $id);
    }

    /**
     * Per-seller money routing: 'platform' (all money to the owner, manual payout)
     * or 'stripe' (settled to the seller's connected Stripe account).
     */
    public function updatePayoutMode(int $id): RedirectResponse
    {
        request()->validate([
            'payout_mode' => 'required|in:platform,stripe',
        ]);

        $seller = $this->sellerRepository->findOrFail($id);
        $seller->update(['payout_mode' => request('payout_mode')]);

        session()->flash('success', 'Roteamento de pagamento atualizado para este vendedor.');

        return redirect()->route('admin.marketplace.sellers.view', $id);
    }
}
