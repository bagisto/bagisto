<?php

namespace Webkul\Marketplace\Http\Controllers\Seller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Webkul\Marketplace\Repositories\MarketplaceOrderRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

class DashboardController extends Controller
{
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected MarketplaceOrderRepository $marketplaceOrderRepository,
    ) {}

    public function index(): View|RedirectResponse
    {
        $customer = auth()->guard('customer')->user();
        $seller = $this->sellerRepository->findByCustomer($customer->id);

        if (! $seller) {
            return redirect()->route('marketplace.register')
                ->with('info', trans('marketplace::app.seller.dashboard.register-first'));
        }

        $pendingEarnings = $this->marketplaceOrderRepository->pendingForSeller($seller->id)->sum('seller_total');
        $totalEarnings = $this->marketplaceOrderRepository->totalEarningsBySeller($seller->id);
        $recentOrders = $this->marketplaceOrderRepository->with('order')
            ->where('seller_id', $seller->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('marketplace::seller.dashboard.index', compact(
            'seller', 'pendingEarnings', 'totalEarnings', 'recentOrders'
        ));
    }
}
