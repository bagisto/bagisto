<?php

namespace Webkul\API\Http\Controllers\Product;

use Webkul\API\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Repositories\ProductReviewRepository as ProductReview;
use Webkul\Product\Helpers\Review;
use Validator;

/**
 * Review controller
 *
 * @author    Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
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
     * Review helper Object
     *
     */
    protected $reviewHelper;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Product\Repositories\ProductRepository        $product
     * @param  Webkul\Product\Repositories\ProductReviewRepository  $productReview
     * @return void
     */
    public function __construct(Product $product, ProductReview $productReview, Review $reviewHelper)
    {
        // $this->middleware('customer')->only(['create', 'store', 'destroy']);

        $this->product = $product;

        $this->productReview = $productReview;

        $this->reviewHelper = $reviewHelper;

        $this->_config = request('_config');
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

        $data = request()->all();

        $customer_id = auth()->guard('customer')->user()->id;

        $data['status'] = 'pending';
        $data['product_id'] = $id;
        $data['customer_id'] = $customer_id;

        $result = $this->productReview->create($data);

        if ($result) {
            return response()->json(['message' => 'success', 'status' => $result]);
        } else {
            return response()->json(['message' => 'failed', 'status' => $result]);
        }
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

        $productReviews = $this->reviewHelper->getReviews($product)->get();

        return $productReviews;
    }

    /**
     * Delete the review of the current product
     *
     * @return response
     */
    public function destroy($id)
    {
        $this->productReview->delete($id);

        session()->flash('success', 'Product Review Successfully Deleted');

        return redirect()->back();
    }
}
