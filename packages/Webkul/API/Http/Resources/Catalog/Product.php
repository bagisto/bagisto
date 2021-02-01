<?php

namespace Webkul\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Create a new resource instance.
     *
     * @return void
     */
    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->productImageHelper = app('Webkul\Product\Helpers\ProductImage');

        $this->productReviewHelper = app('Webkul\Product\Helpers\Review');

        $this->wishlistHelper = app('Webkul\Customer\Helpers\Wishlist');
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        /* assign product */
        $product = $this->product ? $this->product : $this;

        /* get type instance */
        $productTypeInstance = $product->getTypeInstance();

        /* generating resource */
        return [
            /* product information */
            'id'                     => $product->id,
            'sku'                    => $product->sku,
            'type'                   => $product->type,
            'name'                   => $product->name,
            'url_key'                => $product->url_key,
            'price'                  => $productTypeInstance->getMinimalPrice(),
            'formated_price'         => core()->currency($productTypeInstance->getMinimalPrice()),
            'short_description'      => $product->short_description,
            'description'            => $product->description,
            'images'                 => ProductImage::collection($product->images),
            'base_image'             => $this->productImageHelper->getProductBaseImage($product),
            'created_at'             => $product->created_at,
            'updated_at'             => $product->updated_at,

            /* child informations */
            'grouped_products'       => $this->when(
                $productTypeInstance instanceof \Webkul\Product\Type\Grouped,
                $product->grouped_products
            ),
            'bundle_options'        => $this->when(
                $productTypeInstance instanceof \Webkul\Product\Type\Bundle,
                $product->bundle_options
            ),
            'variants'               => $this->when(
                $productTypeInstance instanceof \Webkul\Product\Type\Configurable,
                $product->variants
            ),

            /* super attributes */
            $this->mergeWhen($productTypeInstance->isComposite(), [
                'super_attributes' => Attribute::collection($product->super_attributes),
            ]),

            /* special price cases */
            'special_price'          => $this->when(
                $productTypeInstance->haveSpecialPrice(),
                $productTypeInstance->getSpecialPrice()
            ),
            'formated_special_price' => $this->when(
                $productTypeInstance->haveSpecialPrice(),
                core()->currency($productTypeInstance->getSpecialPrice())
            ),
            'regular_price'          => $this->when(
                $productTypeInstance->haveSpecialPrice(),
                data_get($productTypeInstance->getProductPrices(), 'regular_price.price')
            ),
            'formated_regular_price' => $this->when(
                $productTypeInstance->haveSpecialPrice(),
                data_get($productTypeInstance->getProductPrices(), 'regular_price.formated_price')
            ),

            /* reviews */
            'reviews'                => [
                'total'          => $total = $this->productReviewHelper->getTotalReviews($product),
                'total_rating'   => $total ? $this->productReviewHelper->getTotalRating($product) : 0,
                'average_rating' => $total ? $this->productReviewHelper->getAverageRating($product) : 0,
                'percentage'     => $total ? json_encode($this->productReviewHelper->getPercentageRating($product)) : [],
            ],

            /* product checks */
            'in_stock'               => $product->haveSufficientQuantity(1),
            'is_saved'               => false,
            'is_wishlisted'          => $this->wishlistHelper->getWishlistProduct($product),
            'is_item_in_cart'        => \Cart::hasProduct($product)
        ];
    }
}
