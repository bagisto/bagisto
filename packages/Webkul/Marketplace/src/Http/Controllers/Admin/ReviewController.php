<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Marketplace\DataGrids\Admin\ReviewDataGrid;
use Webkul\Marketplace\Repositories\SellerReviewRepository;

class ReviewController extends Controller
{
    public function __construct(protected SellerReviewRepository $reviewRepository) {}

    /**
     * Display listing of seller reviews.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return app(ReviewDataGrid::class)->toJson();
        }

        return view('marketplace::admin.sellers.reviews');
    }

    /**
     * Update a seller review status.
     */
    public function update(int $id): JsonResponse
    {
        $this->validate(request(), [
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $this->reviewRepository->update(request()->only('status'), $id);

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.sellers.review-update-success'),
        ]);
    }

    /**
     * Delete a seller review.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->reviewRepository->delete($id);

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.sellers.review-delete-success'),
        ]);
    }
}
