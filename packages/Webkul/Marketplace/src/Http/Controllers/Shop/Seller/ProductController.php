<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Marketplace\DataGrids\Shop\SellerProductDataGrid;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Marketplace\Repositories\SellerProductRepository;
use Webkul\Product\Repositories\ProductRepository;

class ProductController extends Controller
{
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected SellerProductRepository $sellerProductRepository,
        protected ProductRepository $productRepository
    ) {}

    /**
     * Display listing of seller's products.
     */
    public function index(): View|JsonResponse
    {
        $customer = auth()->guard('customer')->user();
        $seller = $this->sellerRepository->findByCustomerId($customer->id);

        if (! $seller) {
            return redirect()->route('marketplace.seller.register');
        }

        if (request()->ajax()) {
            return app(SellerProductDataGrid::class)->toJson();
        }

        return view('marketplace::shop.seller.products.index', compact('seller'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        return view('marketplace::shop.seller.products.create');
    }

    /**
     * Store a new product assignment.
     */
    public function store(): RedirectResponse
    {
        request()->validate([
            'product_id'  => 'required|exists:products,id',
            'condition'   => 'required|in:new,used,refurbished',
            'price'       => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:2000',
        ]);

        $customer = auth()->guard('customer')->user();
        $seller = $this->sellerRepository->findByCustomerId($customer->id);

        $approvalRequired = (bool) core()->getConfigData('marketplace.settings.general.product_approval_required');

        $this->sellerProductRepository->create([
            'seller_id'   => $seller->id,
            'product_id'  => request('product_id'),
            'condition'   => request('condition'),
            'price'       => request('price'),
            'description' => request('description'),
            'is_approved' => ! $approvalRequired,
        ]);

        session()->flash('success', trans('marketplace::app.shop.seller.product-added'));

        return redirect()->route('marketplace.seller.products.index');
    }

    /**
     * Show the form for editing a seller product.
     */
    public function edit(int $id): View
    {
        $customer = auth()->guard('customer')->user();
        $seller = $this->sellerRepository->findByCustomerId($customer->id);

        $sellerProduct = $this->sellerProductRepository->findOrFail($id);

        if ($sellerProduct->seller_id !== $seller->id) {
            abort(403);
        }

        return view('marketplace::shop.seller.products.edit', compact('sellerProduct'));
    }

    /**
     * Update a seller product.
     */
    public function update(int $id): RedirectResponse
    {
        request()->validate([
            'condition'   => 'required|in:new,used,refurbished',
            'price'       => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:2000',
        ]);

        $customer = auth()->guard('customer')->user();
        $seller = $this->sellerRepository->findByCustomerId($customer->id);

        $sellerProduct = $this->sellerProductRepository->findOrFail($id);

        if ($sellerProduct->seller_id !== $seller->id) {
            abort(403);
        }

        $this->sellerProductRepository->update(request()->only([
            'condition',
            'price',
            'description',
        ]), $id);

        session()->flash('success', trans('marketplace::app.shop.seller.product-updated'));

        return redirect()->route('marketplace.seller.products.index');
    }

    /**
     * Delete a seller product.
     */
    public function destroy(int $id): JsonResponse
    {
        $customer = auth()->guard('customer')->user();
        $seller = $this->sellerRepository->findByCustomerId($customer->id);

        $sellerProduct = $this->sellerProductRepository->findOrFail($id);

        if ($sellerProduct->seller_id !== $seller->id) {
            abort(403);
        }

        $this->sellerProductRepository->delete($id);

        return new JsonResponse([
            'message' => trans('marketplace::app.shop.seller.product-deleted'),
        ]);
    }
}
