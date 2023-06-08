<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Product\Repositories\ProductReviewRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shop\Http\Resources\ProductReviewResource;

class ReviewController extends APIController
{
    /**
     * Create a controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductReviewRepository  $productReviewRepository
     * @param  
     * @return void
     */
    public function __construct(
        protected ProductRepository $productRepository,
        protected ProductReviewRepository $productReviewRepository
    ) {
    }

    /**
     * Product listings.
     * 
     * @param  integer  $id
     */
    public function index($id): JsonResource
    {
        $product = $this->productRepository->find($id);

        return ProductReviewResource::collection($product->reviews()->paginate(2));
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id)
    {
        $this->validate(request(), [
            'comment' => 'required',
            'rating'  => 'required|numeric|min:1|max:5',
            'title'   => 'required',
        ]);

        request()->merge([
            'status'     => 'pending',
            'product_id' => $id,
        ]);

        if ($customer = auth()->guard('customer')->user()) {
            request()->merge([
                'name'        => $customer->getNameAttribute(),
                'customer_id' => $customer->id
            ]);
        }

        $this->productReviewRepository->create(request()->all());

        return response()->json([
            'message' => trans('shop::app.products.submit-success')
        ], 200);
    }

}
