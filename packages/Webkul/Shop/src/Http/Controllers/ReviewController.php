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
        $this->product = $product;

        $this->productReview = $productReview;

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        $product = $this->product->findBySlugOrFail($slug);

        return view($this->_config['view'], compact('product'));
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
    public function store(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required',
        ]);

        $this->productReview->create(request()->all());

        session()->flash('success', 'Review submitted successfully.');

        return redirect()->route($this->_config['redirect']);
    }
}
