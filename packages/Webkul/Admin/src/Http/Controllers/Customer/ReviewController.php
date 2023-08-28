<?php

namespace Webkul\Admin\Http\Controllers\Customer;

use Illuminate\Support\Facades\Event;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Admin\DataGrids\Customers\ReviewDataGrid;
use Webkul\Product\Repositories\ProductReviewRepository;

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
     * @return JsonResource
     */
    public function edit($id): JsonResource
    {
        $review = $this->productReviewRepository->with(['images', 'product'])->findOrFail($id);

        $review->date = $review->created_at->format('Y-m-d');

        return new JsonResource($review);
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

        session()->flash('success', trans('admin::app.customers.reviews.update-success', ['name' => 'Review']));

        return redirect()->route('admin.customers.customers.review.index');
    }

    /**
     * Delete the review of the current product
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->productReviewRepository->findOrFail($id);

        try {
            Event::dispatch('customer.review.delete.before', $id);

            $this->productReviewRepository->delete($id);

            Event::dispatch('customer.review.delete.after', $id);

            return response()->json(['message' => trans('admin::app.customers.reviews.delete-success', ['name' => 'Review'])]);
        } catch (\Exception $e) {
            report($e);
        }

        return response()->json(['message' => trans('admin::app.response.delete-failed', ['name' => 'Review'])], 500);
    }

    /**
     * Mass delete the reviews on the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $suppressFlash = false;

        if (request()->isMethod('post')) {
            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $index) {
                try {
                    Event::dispatch('customer.review.delete.before', $index);

                    $this->productReviewRepository->delete($index);

                    Event::dispatch('customer.review.delete.after', $index);
                } catch (\Exception $e) {
                    $suppressFlash = true;

                    continue;
                }
            }

            if (! $suppressFlash) {
                session()->flash('success', trans('admin::app.customers.reviews.index.datagrid.delete-success', ['resource' => 'Reviews']));
            } else {
                session()->flash('info', trans('admin::app.customers.reviews.index.datagrid.partial-action', ['resource' => 'Reviews']));
            }

            return redirect()->route('admin.customers.customers.review.index');
        } else {
            session()->flash('error', trans('admin::app.customers.reviews.index.datagrid.method-error'));

            return redirect()->back();
        }
    }

    /**
     * Mass approve the reviews on the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function massUpdate()
    {
        $suppressFlash = false;

        if (request()->isMethod('post')) {
            $data = request()->all();

            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                $review = $this->productReviewRepository->find($value);

                try {
                    if (
                        ! isset($data['mass-action-type'])
                        || $data['mass-action-type'] != 'update'
                    ) {
                        return redirect()->back();
                    }

                    if ($data['update-options'] == 1) {
                        Event::dispatch('customer.review.update.before', $value);

                        $review->update(['status' => 'approved']);

                        Event::dispatch('customer.review.update.after', $review);
                    } elseif ($data['update-options'] == 0) {
                        $review->update(['status' => 'pending']);
                    } elseif ($data['update-options'] == 2) {
                        $review->update(['status' => 'disapproved']);
                    } else {
                        continue;
                    }
                } catch (\Exception $e) {
                    $suppressFlash = true;

                    continue;
                }
            }

            if (! $suppressFlash) {
                session()->flash('success', trans('admin::app.customers.reviews.index.datagrid.update-success', ['resource' => 'Reviews']));
            } else {
                session()->flash('info', trans('admin::app.customers.reviews.index.datagrid.partial-action', ['resource' => 'Reviews']));
            }

            return redirect()->route('admin.customers.customers.review.index');
        } else {
            session()->flash('error', trans('admin::app.customers.reviews.index.datagrid.method-error'));

            return redirect()->back();
        }
    }
}
