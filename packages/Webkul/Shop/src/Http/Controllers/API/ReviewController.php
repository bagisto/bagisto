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
     */
    public function store($id): JsonResource
    {
        $this->validate(request(), [
            'comment' => 'required',
            'rating'  => 'required|numeric|min:1|max:5',
            'title'   => 'required',
        ]);

        $data = [
            'comment' => request()->input('comment'),
            'rating' => request()->input('rating'),
            'title' => request()->input('title'),
            'status'     => 'pending',
            'product_id' => $id,
        ];

        if ($customer = auth()->guard('customer')->user()) {
            $data = array_merge($data, [
                'name'        => $customer->name,
                'customer_id' => $customer->id,
            ]);
        }

        $this->productReviewRepository->create($data);

        return new JsonResource([
            'message' => trans('shop::app.products.submit-success'),
        ]);
    }
}
