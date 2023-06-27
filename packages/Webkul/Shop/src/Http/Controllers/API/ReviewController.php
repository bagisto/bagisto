<?php

namespace Webkul\Shop\Http\Controllers\API;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Repositories\ProductReviewAttachmentRepository;
use Webkul\Product\Repositories\ProductReviewRepository;
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
        protected ProductReviewRepository $productReviewRepository,
        protected ProductReviewAttachmentRepository $productReviewAttachmentRepository
    ) {
    }

    /**
     * Using const variable for status
     */
    const STATUS_APPROVED = 'approved';

    const STATUS_PENDING = ' pending';

    /**
     * Product listings.
     *
     * @param  int  $id
     */
    public function index($id): JsonResource
    {
        $product = $this->productRepository
            ->find($id)
            ->reviews()
            ->Where('status', self::STATUS_APPROVED)
            ->paginate(8);

        return ProductReviewResource::collection($product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $id
     */
    public function store($id): JsonResource
    {
        $this->validate(request(), [
            'title'   => 'required',
            'comment' => 'required',
            'rating'  => 'required|numeric|min:1|max:5',
        ]);

        $data = array_merge(request()->all(), [
            'status'     => self::STATUS_PENDING,
            'product_id' => $id,
        ]);

        if ($customer = auth()->guard('customer')->user()) {
            $data = array_merge($data, [
                'name'        => $customer->name,
                'customer_id' => $customer->id,
            ]);
        }

        $review = $this->productReviewRepository->create($data);

        $this->productReviewAttachmentRepository->uploadImages($data, $review);

        return new JsonResource([
            'message' => trans('shop::app.products.submit-success'),
        ]);
    }
}
