<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Marketplace\DataGrids\Admin\SellerDataGrid;
use Webkul\Marketplace\Repositories\SellerRepository;

class SellerController extends Controller
{
    public function __construct(protected SellerRepository $sellerRepository) {}

    /**
     * Display a listing of sellers.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return app(SellerDataGrid::class)->toJson();
        }

        return view('marketplace::admin.sellers.index');
    }

    /**
     * Show the form for editing a seller.
     */
    public function edit(int $id): View
    {
        $seller = $this->sellerRepository->findOrFail($id);

        return view('marketplace::admin.sellers.edit', compact('seller'));
    }

    /**
     * Update the seller.
     */
    public function update(int $id): RedirectResponse
    {
        $this->validate(request(), [
            'commission_percentage' => 'nullable|numeric|min:0|max:100',
            'is_approved'           => 'boolean',
            'status'                => 'boolean',
        ]);

        $this->sellerRepository->update(request()->only([
            'commission_percentage',
            'is_approved',
            'status',
        ]), $id);

        session()->flash('success', trans('marketplace::app.admin.sellers.update-success'));

        return redirect()->route('admin.marketplace.sellers.index');
    }

    /**
     * Delete a seller.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->sellerRepository->delete($id);

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.sellers.delete-success'),
        ]);
    }

    /**
     * Approve a seller.
     */
    public function approve(int $id): RedirectResponse
    {
        $this->sellerRepository->update(['is_approved' => true], $id);

        session()->flash('success', trans('marketplace::app.admin.sellers.approve-success'));

        return redirect()->route('admin.marketplace.sellers.index');
    }
}
