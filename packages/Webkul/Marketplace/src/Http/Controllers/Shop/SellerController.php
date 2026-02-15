<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Marketplace\Repositories\SellerReviewRepository;

class SellerController extends Controller
{
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected SellerReviewRepository $reviewRepository
    ) {}

    /**
     * Display listing of all active sellers.
     */
    public function index(): View
    {
        $sellers = $this->sellerRepository->getActiveSellers();

        return view('marketplace::shop.sellers.index', compact('sellers'));
    }

    /**
     * Show a seller's public profile page.
     */
    public function show(string $url): View
    {
        $seller = $this->sellerRepository->findByUrl($url);

        if (! $seller || ! $seller->is_approved || ! $seller->status) {
            abort(404);
        }

        $reviews = $this->reviewRepository->getApprovedReviews($seller->id);

        return view('marketplace::shop.sellers.show', compact('seller', 'reviews'));
    }

    /**
     * Store a review for a seller.
     */
    public function storeReview(string $url): RedirectResponse
    {
        $seller = $this->sellerRepository->findByUrl($url);

        if (! $seller) {
            abort(404);
        }

        request()->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'title'   => 'required|string|max:255',
            'comment' => 'required|string|max:2000',
        ]);

        $this->reviewRepository->create([
            'seller_id'   => $seller->id,
            'customer_id' => auth()->guard('customer')->id(),
            'rating'      => request('rating'),
            'title'       => request('title'),
            'comment'     => request('comment'),
            'status'      => 'pending',
        ]);

        session()->flash('success', trans('marketplace::app.shop.sellers.review-submitted'));

        return redirect()->back();
    }
}
