<?php

namespace Webkul\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Product\Helpers\ProductType;

class Product extends JsonResource
{
    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct($resource)
    {
        $this->productImageHelper = app('Webkul\Product\Helpers\ProductImage');

        $this->productReviewHelper = app('Webkul\Product\Helpers\Review');

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $product = $this->product ? $this->product : $this;

        return [
            'id'                     => $product->id,
            'type'                   => $product->type,
            'name'                   => $this->name,
            'url_key'                => $this->url_key,
            'price'                  => $product->getTypeInstance()->getMinimalPrice(),
            'formated_price'         => core()->currency($product->getTypeInstance()->getMinimalPrice()),
            'short_description'      => $this->short_description,
            'description'            => $this->description,
            'sku'                    => $this->sku,
            'images'                 => ProductImage::collection($product->images),
            'base_image'             => $this->productImageHelper->getProductBaseImage($product),
            'variants'               => Self::collection($this->variants),
            'in_stock'               => $product->haveSufficientQuantity(1),
            $this->mergeWhen($product->getTypeInstance()->isComposite(), [
                'super_attributes' => Attribute::collection($product->super_attributes),
            ]),
            'special_price'          => $this->when(
                    $product->getTypeInstance()->haveSpecialPrice(),
                    $product->getTypeInstance()->getSpecialPrice()
                ),
            'formated_special_price' => $this->when(
                    $product->getTypeInstance()->haveSpecialPrice(),
                    core()->currency($product->getTypeInstance()->getSpecialPrice())
                ),
            'reviews'                => [
                'total'          => $total = $this->productReviewHelper->getTotalReviews($product),
                'total_rating'   => $total ? $this->productReviewHelper->getTotalRating($product) : 0,
                'average_rating' => $total ? $this->productReviewHelper->getAverageRating($product) : 0,
                'percentage'     => $total ? json_encode($this->productReviewHelper->getPercentageRating($product)) : [],
            ],
            'is_saved'               => false,
            'created_at'             => $this->created_at,
            'updated_at'             => $this->updated_at,
        ];
    }
}