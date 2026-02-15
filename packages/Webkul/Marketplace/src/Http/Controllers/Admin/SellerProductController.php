<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Marketplace\DataGrids\Admin\SellerProductDataGrid;
use Webkul\Marketplace\Repositories\SellerProductRepository;

class SellerProductController extends Controller
{
    public function __construct(protected SellerProductRepository $sellerProductRepository) {}

    /**
     * Display listing of seller products.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return app(SellerProductDataGrid::class)->toJson();
        }

        return view('marketplace::admin.sellers.products');
    }

    /**
     * Approve a seller product.
     */
    public function approve(int $id): JsonResponse
    {
        $this->sellerProductRepository->update(['is_approved' => true], $id);

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.sellers.product-approve-success'),
        ]);
    }

    /**
     * Delete a seller product.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->sellerProductRepository->delete($id);

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.sellers.product-delete-success'),
        ]);
    }
}
