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
     * Product review repository instance.
     *
     * @var \Webkul\Product\Repositories\ProductReviewRepository
     */
    protected $productReviewRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductReviewRepository  $productReview
     * @return void
     */
    public function __construct(ProductReviewRepository $productReviewRepository)
    {
        $this->productReviewRepository = $productReviewRepository;

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
        $this->productReviewRepository->update(request()->all(), $id);

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
            $this->productReviewRepository->delete($id);

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
            $data = request()->all();

            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                try {
                    $this->productReviewRepository->delete($value);
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
                $review = $this->productReviewRepository->findOneByField('id', $value);

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
