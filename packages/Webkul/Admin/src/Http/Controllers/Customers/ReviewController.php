<?php

namespace Webkul\Admin\Http\Controllers\Customers;

use Illuminate\Support\Facades\Event;
use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\Http\Requests\MassDestroyRequest;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\Admin\DataGrids\Customers\ReviewDataGrid;

class ReviewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductReviewRepository  $productReview
     * @return void
     */
    public function __construct(protected ProductReviewRepository $productReviewRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(ReviewDataGrid::class)->toJson();
        }

        return view('admin::customers.reviews.index');
    }

    /**
     * Review Details
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id): JsonResponse
    {
        $review = $this->productReviewRepository->with(['images', 'product'])->findOrFail($id);

        $review->date = $review->created_at->format('Y-m-d');

        return new JsonResponse([
            'data' => $review
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        Event::dispatch('customer.review.update.before', $id);

        $review = $this->productReviewRepository->update(request()->only(['status']), $id);

        Event::dispatch('customer.review.update.after', $review);

        session()->flash('success', trans('admin::app.customers.reviews.update-success', ['name' => 'admin::app.customers.reviews.review']));

        return redirect()->route('admin.customers.customers.review.index');
    }

    /**
     * Delete the review of the current product
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $this->productReviewRepository->findOrFail($id);

        try {
            Event::dispatch('customer.review.delete.before', $id);

            $this->productReviewRepository->delete($id);

            Event::dispatch('customer.review.delete.after', $id);

            return new JsonResponse(['message' => trans('admin::app.customers.reviews.index.datagrid.delete-success', ['name' => 'Review'])]);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => trans('admin::app.response.delete-failed', ['name' => 'Review'])], 500);
        }
    }

    /**
     * Mass delete the reviews on the products.
     *
     * @param MassDestroyRequest $massDestroyRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function massDestroy(MassDestroyRequest $massDestroyRequest): JsonResponse
    {
        $indices = $massDestroyRequest->input('indices');

        try {
            foreach ($indices as $index) {
                Event::dispatch('customer.review.delete.before', $index);

                $this->productReviewRepository->delete($index);

                Event::dispatch('customer.review.delete.after', $index);
            }

            return new JsonResponse([
                'message' => trans('admin::app.customers.reviews.index.datagrid.mass-delete-success')
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mass approve the reviews on the products.
     *
     * @param MassUpdateRequest $massUpdateRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function massUpdate(MassUpdateRequest $massUpdateRequest): JsonResponse
    {
        $indices = $massUpdateRequest->input('indices');

        foreach ($indices as $id) {
            Event::dispatch('customer.review.update.before', $id);

            $review = $this->productReviewRepository->update([
                'status' => $massUpdateRequest->input('value'),
            ], $id);

            Event::dispatch('customer.review.update.after', $review);
        }

        return new JsonResponse([
            'message' => trans('admin::app.customers.reviews.index.datagrid.mass-update-success')
        ], 200);
    }
}