<?php

namespace Webkul\Marketplace\Http\Controllers\Seller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Webkul\Marketplace\Repositories\MarketplaceOrderRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

class OrderController extends Controller
{
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected MarketplaceOrderRepository $marketplaceOrderRepository,
    ) {}

    public function index(): View|RedirectResponse
    {
        $seller = $this->getSeller();

        if (! $seller) {
            return redirect()->route('marketplace.register');
        }

        $orders = $this->marketplaceOrderRepository->with('order')
            ->where('seller_id', $seller->id)
            ->latest()
            ->paginate(20);

        return view('marketplace::seller.orders.index', compact('seller', 'orders'));
    }

    public function view(int $id): View|RedirectResponse
    {
        $seller = $this->getSeller();

        if (! $seller) {
            return redirect()->route('marketplace.register');
        }

        $order = $this->marketplaceOrderRepository->with('order')
            ->where('seller_id', $seller->id)
            ->findOrFail($id);

        return view('marketplace::seller.orders.view', compact('seller', 'order'));
    }

    protected function getSeller(): ?object
    {
        $customer = auth()->guard('customer')->user();

        return $this->sellerRepository->findByCustomer($customer->id);
    }
}
