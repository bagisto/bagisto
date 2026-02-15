<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Marketplace\DataGrids\Admin\SellerOrderDataGrid;
use Webkul\Marketplace\Repositories\SellerOrderRepository;

class SellerOrderController extends Controller
{
    public function __construct(protected SellerOrderRepository $sellerOrderRepository) {}

    /**
     * Display listing of seller orders.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return app(SellerOrderDataGrid::class)->toJson();
        }

        return view('marketplace::admin.sellers.orders');
    }

    /**
     * View a seller order.
     */
    public function view(int $id): View
    {
        $sellerOrder = $this->sellerOrderRepository->findOrFail($id);

        return view('marketplace::admin.sellers.order-view', compact('sellerOrder'));
    }
}
