<?php

namespace Webkul\API\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\API\Http\Resources\Catalog\ProductReview as ProductReviewResource;

class ReviewController extends Controller
{
    /**
     * Contains current guard.
     *
     * @var array
     */
    protected $guard;

    /**
     * ProductReviewRepository $reviewRepository
     *
     * @var \Webkul\Product\Repositories\ProductReviewRepository
     */
    protected $reviewRepository;

    /**
     * Controller instance.
     *
     * @param  Webkul\Product\Repositories\ProductReviewRepository  $reviewRepository
     */
    public function __construct(ProductReviewRepository $reviewRepository)
    {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        auth()->setDefaultDriver($this->guard);

        $this->reviewRepository = $reviewRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $customer = auth($this->guard)->user();

        $this->validate($request, [
            'comment' => 'required',
            'rating'  => 'required|numeric|min:1|max:5',
            'title'   => 'required',
        ]);

        $productReview = $this->reviewRepository->create([
            'customer_id' => $customer ? $customer->id : null,
            'name'        => $customer ? $customer->name : $request->get('name'),
            'status'      => 'pending',
            'product_id'  => $id,
            'comment'     => $request->comment,
            'rating'      => $request->rating,
            'title'       => $request->title
        ]);

        return response()->json([
            'message' => 'Your review submitted successfully.',
            'data'    => new ProductReviewResource($productReview),
        ]);
    }
}