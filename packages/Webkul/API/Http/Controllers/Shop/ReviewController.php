<?php

namespace Webkul\API\Http\Controllers\Shop;

use Illuminate\Http\Request;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\API\Http\Resources\Catalog\ProductReview as ProductReviewResource;

/**
 * Review controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ReviewController extends Controller
{
    /**
     * Contains current guard
     *
     * @var array
     */
    protected $guard;

    /**
     * ProductReviewRepository object
     *
     * @var array
     */
    protected $reviewRepository;

    /**
     * Controller instance
     *
     * @param Webkul\Product\Repositories\ProductReviewRepository $reviewRepository
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $customer = auth($this->guard)->user();

        $this->validate(request(), [
            'comment' => 'required',
            'rating'  => 'required|numeric|min:1|max:5',
            'title'   => 'required',
        ]);

        $data = array_merge(request()->all(), [
            'customer_id' => $customer ? $customer->id : null,
            'name'        => $customer ? $customer->name : request()->input('name'),
            'status'      => 'pending',
            'product_id'  => $id
        ]);

        $productReview = $this->reviewRepository->create($data);

        return response()->json([
            'message' => 'Your review submitted successfully.',
            'data'    => new ProductReviewResource($this->reviewRepository->find($productReview->id))
        ]);
    }
}