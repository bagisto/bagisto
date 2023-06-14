<?php

namespace Webkul\Shop\Http\Controllers;

use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\Product\Repositories\ProductReviewImageRepository;

class ReviewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @param  \Webkul\Product\Repositories\ProductReviewRepository  $productReviewRepository
     * @param  \Webkul\Product\Repositories\ProductReviewImageRepository  $productReviewImageRepository
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductReviewRepository $productReviewRepository,
        protected ProductReviewImageRepository $productReviewImageRepository
    )
    {
        parent::__construct();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View|\Exception
     */
    public function create($slug)
    {
        if (
            auth()->guard('customer')->check()
            || core()->getConfigData('catalog.products.review.guest_review')
        ) {
            $product = $this->productRepository->findBySlug($slug);

            if ($product == null) {
                session()->flash('error', trans('customer::app.product-removed'));

                return redirect()->back();
            }

            return view($this->_config['view'], compact('product'));
        }

        session()->flash('error', trans('shop::app.reviews.login-to-review'));

        return redirect()->route('shop.customer.session.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        $this->validate(request(), [
            'comment' => 'required',
            'rating'  => 'required|numeric|min:1|max:5',
            'title'   => 'required',
        ]);

        $product = $this->productRepository->find($id);

        $data = array_merge(request()->all(), [
            'status'     => 'pending',
            'product_id' => $id,
        ]);

        if (auth()->guard('customer')->user()) {
            $data['customer_id'] = auth()->guard('customer')->user()->id;

            $data['name'] = auth()->guard('customer')->user()->first_name . ' ' . auth()->guard('customer')->user()->last_name;
        }

        $review = $this->productReviewRepository->create($data);

        $this->productReviewImageRepository->uploadImages($data, $review);

        session()->flash('success', trans('shop::app.response.submit-success', ['name' => 'Product Review']));

        return redirect()->route('shop.productOrCategory.index', $product->url_key);
    }

    /**
     * Display reviews of particular product.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
    */
    public function show($slug)
    {
        $product = $this->productRepository->findBySlugOrFail($slug);

        return view($this->_config['view'], compact('product'));
    }

    /**
     * Customer delete a reviews from their account
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review = $this->productReviewRepository->findOneWhere([
            'id'          => $id,
            'customer_id' => auth()->guard('customer')->user()->id,
        ]);

        if (! $review) {
            abort(404);
        }

        $this->productReviewRepository->delete($id);

        session()->flash('success', trans('shop::app.response.delete-success', ['name' => 'Product Review']));

        return redirect()->route($this->_config['redirect']);
    }

    /**
     * Customer delete all reviews from their account
     *
     * @return \Illuminate\Http\Response
    */
    public function deleteAll()
    {
        $reviews = auth()->guard('customer')->user()->all_reviews;

        foreach ($reviews as $review) {
            $this->productReviewRepository->delete($review->id);
        }

        session()->flash('success', trans('shop::app.reviews.delete-all'));

        return redirect()->route($this->_config['redirect']);
    }
}