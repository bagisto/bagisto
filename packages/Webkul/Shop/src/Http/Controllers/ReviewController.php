<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Repositories\ProductReviewRepository as ProductReview;

/**
 * Review controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
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
     * @param  Webkul\Product\Repositories\ProductRepository        $product
     * @param  Webkul\Product\Repositories\ProductReviewRepository  $productReview
     * @return void
     */
    public function __construct(Product $product, ProductReview $productReview)
    {
        $this->middleware('admin')->only(['update', 'destroy']);

        $this->middleware('customer')->only(['create', 'store', 'destroy']);

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
     * Show the form for creating a new resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function create($slug)
    {
        $product = $this->product->findBySlugOrFail($slug);

        return view($this->_config['view'], compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , $id)
    {
        $this->validate(request(), [
            'comment' => 'required',
            'rating'  => 'required',
            'title'   => 'required',
        ]);

        $data=$request->all();

        $customer_id = auth()->guard('customer')->user()->id;

        $data['status'] = 'pending';
        $data['product_id'] = $id;
        $data['customer_id'] = $customer_id;

        $this->productReview->create($data);

        session()->flash('success', 'Review submitted successfully.');

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Display reviews accroding to product.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
    */
    public function show($slug)
    {
        $product = $this->product->findBySlugOrFail($slug);

        return view($this->_config['view'],compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $review = $this->productReview->find($id);

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
        $this->productReview->update(request()->all(), $id);

        session()->flash('success', 'Review updated successfully.');

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Delete the review of the current product
     *
     * @return response
     */
    public function destroy($id)
    {
        $this->productReview->delete($id);

        success()->flash('success', 'Product Review Successfully Deleted');

        return redirect()->back();
    }
}
