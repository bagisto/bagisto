<?php

namespace Webkul\Marketplace\Http\Controllers\Seller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Webkul\Marketplace\Repositories\MarketplaceOrderRepository;
use Webkul\Marketplace\Repositories\PayoutRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

class PayoutController extends Controller
{
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected PayoutRepository $payoutRepository,
        protected MarketplaceOrderRepository $marketplaceOrderRepository,
    ) {}

    public function index(): View|RedirectResponse
    {
        $seller = $this->getSeller();

        if (! $seller) {
            return redirect()->route('marketplace.register');
        }

        $payouts = $this->payoutRepository->where('seller_id', $seller->id)
            ->latest()
            ->paginate(20);

        $availableBalance = $this->marketplaceOrderRepository->totalEarningsBySeller($seller->id);

        return view('marketplace::seller.payouts.index', compact('seller', 'payouts', 'availableBalance'));
    }

    public function request(): RedirectResponse
    {
        $seller = $this->getSeller();

        if (! $seller) {
            return redirect()->route('marketplace.register');
        }

        $minPayout = config('marketplace.min_payout_amount', 50);

        request()->validate([
            'amount'         => "required|numeric|min:{$minPayout}",
            'payment_method' => 'required|in:pix,bank_transfer,paypal',
            'payment_details' => 'required|array',
        ]);

        $this->payoutRepository->requestPayout(
            $seller->id,
            request('amount'),
            request('payment_method'),
            request('payment_details')
        );

        return redirect()->route('marketplace.payouts.index')
            ->with('success', trans('marketplace::app.seller.payouts.request-success'));
    }

    protected function getSeller(): ?object
    {
        $customer = auth()->guard('customer')->user();

        return $this->sellerRepository->findByCustomer($customer->id);
    }
}
