<?php

namespace Webkul\Marketplace\Http\Controllers\Seller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Webkul\Marketplace\Repositories\SellerProductRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Repositories\ProductRepository;

class ProductController extends Controller
{
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected SellerProductRepository $sellerProductRepository,
        protected ProductRepository $productRepository,
    ) {}

    public function index(): View|RedirectResponse
    {
        $seller = $this->getSeller();

        if (! $seller) {
            return redirect()->route('marketplace.register');
        }

        $products = $this->sellerProductRepository->with('product')
            ->where('seller_id', $seller->id)
            ->paginate(20);

        return view('marketplace::seller.products.index', compact('seller', 'products'));
    }

    public function create(): View|RedirectResponse
    {
        $seller = $this->getSeller();

        if (! $seller) {
            return redirect()->route('marketplace.register');
        }

        return view('marketplace::seller.products.create', compact('seller'));
    }

    public function store(): RedirectResponse
    {
        $seller = $this->getSeller();

        if (! $seller) {
            return redirect()->route('marketplace.register');
        }

        request()->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $productId = (int) request('product_id');

        if ($this->sellerProductRepository->findByProduct($productId)?->seller_id === $seller->id) {
            return back()->with('error', trans('marketplace::app.seller.products.already-listed'));
        }

        $this->sellerProductRepository->create([
            'seller_id'  => $seller->id,
            'product_id' => $productId,
            'status'     => config('marketplace.auto_approve_products') ? 'approved' : 'pending',
        ]);

        return redirect()->route('marketplace.products.index')
            ->with('success', trans('marketplace::app.seller.products.submit-success'));
    }

    protected function getSeller(): ?object
    {
        $customer = auth()->guard('customer')->user();

        return $this->sellerRepository->findByCustomer($customer->id);
    }
}
