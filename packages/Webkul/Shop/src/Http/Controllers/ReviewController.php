<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductReviewRepository;

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
     * @var Object
     */
    protected $productRepository;

    /**
     * ProductReviewRepository object
     *
     * @var Object
     */
    protected $productReviewRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository        $productRepository
     * @param  \Webkul\Product\Repositories\ProductReviewRepository  $productReviewRepository
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        ProductReviewRepository $productReviewRepository
    ) {
        $this->productRepository = $productRepository;

        $this->productReviewRepository = $productReviewRepository;

        $this->_config = request('_config');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string $slug
     * @return \Illuminate\View\View
     */
    public function create($slug)
    {
        if (auth()->guard('customer')->check() || core()->getConfigData('catalog.products.review.guest_review')) {
            $product = $this->productRepository->findBySlugOrFail($slug);

            return view($this->_config['view'], compact('product', 'guest_review'));
        }

        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        $this->validate(request(), [
            'comment' => 'required',
            'rating'  => 'required|numeric|min:1|max:5',
            'title'   => 'required',
        ]);

        $data = request()->all();

        if (auth()->guard('customer')->user()) {
            $data['customer_id'] = auth()->guard('customer')->user()->id;
            $data['name'] = auth()->guard('customer')->user()->first_name .' ' . auth()->guard('customer')->user()->last_name;
        }

        $data['status'] = 'pending';
        $data['product_id'] = $id;

        $this->productReviewRepository->create($data);

        session()->flash('success', trans('shop::app.response.submit-success', ['name' => 'Product Review']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Display reviews of particular product.
     *
     * @param  string $slug
     * @return \Illuminate\View\View
    */
    public function show($slug)
    {
        $product = $this->productRepository->findBySlugOrFail($slug);

        return view($this->_config['view'],compact('product'));
    }

    /**
     * Customer delete a reviews from their account
     *
     * @param integer $id
     * @return response
     */
    public function destroy($id)
    {
        $review = $this->productReviewRepository->findOneWhere([
            'id' => $id,
            'customer_id' => auth()->guard('customer')->user()->id
        ]);

        if (! $review)
            abort(404);

        $this->productReviewRepository->delete($id);

        session()->flash('success', trans('shop::app.response.delete-success', ['name' => 'Product Review']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Customer delete all reviews from their account
     *
     * @return Mixed Response & Boolean
    */
    public function deleteAll()
    {
        $reviews = auth()->guard('customer')->user()->all_reviews;

        if ($reviews->count() > 0) {
            foreach ($reviews as $review) {
                $this->productReviewRepository->delete($review->id);
            }
        }

        session()->flash('success', trans('shop::app.reviews.delete-all'));

        return redirect()->route($this->_config['redirect']);
    }
}