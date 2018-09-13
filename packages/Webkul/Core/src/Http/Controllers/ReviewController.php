<?php

namespace Webkul\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * @param  Webkul\Product\Repositories\ProductRepository        $product
     * @param  Webkul\Product\Repositories\ProductReviewRepository  $productReview
     * @return void
     */
    public function __construct(Product $product, ProductReview $productReview)
    {
        $this->product = $product;

        $this->productReview = $productReview;

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
        ]);

        $input=$request->all();

        $input['product_id']=$id;

        $input['customer_id']=1;

        $this->productReview->create($input);

        session()->flash('success', 'Review submitted successfully.');

        return redirect()->route($this->_config['redirect']);
    }
    
}
