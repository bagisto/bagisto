<?php

namespace Webkul\Product\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Admin\DataGrids\CustomerReviewDataGrid;
use Webkul\Product\Repositories\ProductReviewRepository;

class ReviewController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductReviewRepository  $productReview
     * @return void
     */
    public function __construct(protected ProductReviewRepository $productReviewRepository)
    {
        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(CustomerReviewDataGrid::class)->toJson();
        }

        return view($this->_config['view']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $review = $this->productReviewRepository->findOrFail($id);

        return view($this->_config['view'], compact('review'));
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

        $review = $this->productReviewRepository->update(request()->all(), $id);

        Event::dispatch('customer.review.update.after', $review);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Review']));

        return redirect()->route($this->_config['redirect']);
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

            return response()->json(['message' => trans('admin::app.response.delete-success', ['name' => 'Review'])]);
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
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', ['resource' => 'Reviews']));
            } else {
                session()->flash('info', trans('admin::app.datagrid.mass-ops.partial-action', ['resource' => 'Reviews']));
            }

            return redirect()->route($this->_config['redirect']);

        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));

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
                    if (! isset($data['massaction-type'])) {
                        return redirect()->back();
                    }

                    if (! $data['massaction-type'] == 'update') {
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
                session()->flash('success', trans('admin::app.datagrid.mass-ops.update-success', ['resource' => 'Reviews']));
            } else {
                session()->flash('info', trans('admin::app.datagrid.mass-ops.partial-action', ['resource' => 'Reviews']));
            }

            return redirect()->route($this->_config['redirect']);
        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));

            return redirect()->back();
        }
    }
}
