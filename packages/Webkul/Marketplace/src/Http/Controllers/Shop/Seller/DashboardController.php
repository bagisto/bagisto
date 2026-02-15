<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Marketplace\Repositories\SellerOrderRepository;
use Webkul\Marketplace\Repositories\SellerTransactionRepository;

class DashboardController extends Controller
{
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected SellerOrderRepository $orderRepository,
        protected SellerTransactionRepository $transactionRepository
    ) {}

    /**
     * Display the seller dashboard.
     */
    public function index(): View|RedirectResponse
    {
        $customer = auth()->guard('customer')->user();
        $seller = $this->sellerRepository->findByCustomerId($customer->id);

        if (! $seller) {
            return redirect()->route('marketplace.seller.register');
        }

        $earnings = $this->orderRepository->getEarningsSummary($seller->id);
        $balance = $this->transactionRepository->getBalance($seller->id);

        return view('marketplace::shop.seller.dashboard.index', compact(
            'seller',
            'earnings',
            'balance'
        ));
    }
}
