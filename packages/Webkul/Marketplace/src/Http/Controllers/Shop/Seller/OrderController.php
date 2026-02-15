<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Marketplace\DataGrids\Shop\SellerOrderDataGrid;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Marketplace\Repositories\SellerOrderRepository;

class OrderController extends Controller
{
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected SellerOrderRepository $sellerOrderRepository
    ) {}

    /**
     * Display listing of seller's orders.
     */
    public function index(): View|JsonResponse
    {
        $customer = auth()->guard('customer')->user();
        $seller = $this->sellerRepository->findByCustomerId($customer->id);

        if (! $seller) {
            return redirect()->route('marketplace.seller.register');
        }

        if (request()->ajax()) {
            return app(SellerOrderDataGrid::class)->toJson();
        }

        return view('marketplace::shop.seller.orders.index', compact('seller'));
    }

    /**
     * View seller order details.
     */
    public function view(int $id): View
    {
        $customer = auth()->guard('customer')->user();
        $seller = $this->sellerRepository->findByCustomerId($customer->id);

        $sellerOrder = $this->sellerOrderRepository->findOrFail($id);

        if ($sellerOrder->seller_id !== $seller->id) {
            abort(403);
        }

        return view('marketplace::shop.seller.orders.view', compact('sellerOrder', 'seller'));
    }
}
