<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller;

use Illuminate\View\View;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Marketplace\Repositories\SellerReviewRepository;

class ReviewController extends Controller
{
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected SellerReviewRepository $reviewRepository
    ) {}

    /**
     * Display listing of reviews for the seller.
     */
    public function index(): View
    {
        $customer = auth()->guard('customer')->user();
        $seller = $this->sellerRepository->findByCustomerId($customer->id);

        if (! $seller) {
            return redirect()->route('marketplace.seller.register');
        }

        $reviews = $this->reviewRepository->findByField('seller_id', $seller->id);

        return view('marketplace::shop.seller.reviews.index', compact('seller', 'reviews'));
    }
}
