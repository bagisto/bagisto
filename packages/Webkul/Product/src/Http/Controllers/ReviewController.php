<?php

namespace Webkul\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Repositories\ProductReviewRepository as ProductReview;

/**
 * Review controller
 *
 * @author    Rahul Shukla <rahulshukla.symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ReviewController extends Controller
{

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * ProductRepository object
     *
     * @var array
     */
    protected $product;

    /**
     * ProductReviewRepository object
     *
     * @var array
     */
    protected $productReview;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository        $product
     * @param  \Webkul\Product\Repositories\ProductReviewRepository  $productReview
     * @return void
     */
    public function __construct(Product $product, ProductReview $productReview)
    {
        $this->product = $product;

        $this->productReview = $productReview;

        $this->_config = request('_config');
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $review = $this->productReview->findOrFail($id);

        return view($this->_config['view'],compact('review'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Event::fire('customer.review.update.before', $id);

        $this->productReview->update(request()->all(), $id);

        Event::fire('customer.review.update.after', $id);

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
        Event::fire('customer.review.delete.before', $id);

        $this->productReview->delete($id);

        Event::fire('customer.review.delete.after', $id);

        session()->flash('success', trans('admin::app.response.delete-success', ['name' => 'Review']));

        return redirect()->back();
    }

    /**
     * Mass delete the reviews on the products.
     *
     * @return response
     */
    public function massDestroy() {
        $suppressFlash = false;

        if (request()->isMethod('post')) {
            $data = request()->all();

            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                try {
                    Event::fire('customer.review.delete.before', $value);

                    $this->productReview->delete($value);

                    Event::fire('customer.review.delete.after', $value);
                } catch(\Exception $e) {
                    $suppressFlash = true;

                    continue;
                }
            }

            if (! $suppressFlash)
                session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', ['resource' => 'Reviews']));
            else
                session()->flash('info', trans('admin::app.datagrid.mass-ops.partial-action', ['resource' => 'Reviews']));

            return redirect()->route($this->_config['redirect']);

        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));

            return redirect()->back();
        }
    }

    /**
     * Mass approve the reviews on the products.
     *
     * @return response
     */
    public function massUpdate() {
        $suppressFlash = false;

        if (request()->isMethod('post')) {
            $data = request()->all();

            $indexes = explode(',', request()->input('indexes'));

            foreach ($indexes as $key => $value) {
                $review = $this->productReview->findOneByField('id', $value);

                try {
                    if ($data['massaction-type'] == 'update') {
                        if ($data['update-options'] == 1) {
                            Event::fire('customer.review.update.before', $value);

                            $review->update(['status' => 'approved']);

                            Event::fire('customer.review.update.after', $review);
                        } else if ($data['update-options'] == 0) {
                            $review->update(['status' => 'pending']);
                        } else if ($data['update-options'] == 2) {
                            $review->update(['status' => 'disapproved']);
                        } else {
                            continue;
                        }
                    }
                } catch(\Exception $e) {
                    $suppressFlash = true;

                    continue;
                }
            }

            if (! $suppressFlash)
                session()->flash('success', trans('admin::app.datagrid.mass-ops.update-success', ['resource' => 'Reviews']));
            else
                session()->flash('info', trans('admin::app.datagrid.mass-ops.partial-action', ['resource' => 'Reviews']));

            return redirect()->route($this->_config['redirect']);
        } else {
            session()->flash('error', trans('admin::app.datagrid.mass-ops.method-error'));

            return redirect()->back();
        }
    }
}
