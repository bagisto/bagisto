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
        $this->productPriceHelper = app('Webkul\Product\Helpers\Price');

        $this->productImageHelper = app('Webkul\Product\Helpers\ProductImage');

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
            'id' => $product->id,
            'type' => $product->type,
            'name' => $this->name,
            'price' => $this->price,
            'formated_price' => core()->currency($this->price),
            'description' => $this->description,
            'sku' => $this->sku,
            'images' => ProductImage::collection($product->images),
            'base_image' => $this->productImageHelper->getProductBaseImage($product),
            'variants' => Self::collection($this->variants),
            'in_stock' => $product->haveSufficientQuantity(1),
            $this->mergeWhen($product->type == 'configurable', [
                'super_attributes' => Attribute::collection($product->super_attributes),
            ]),
            'special_price' => $this->when(
                    $this->productPriceHelper->haveSpecialPrice($product),
                    $this->productPriceHelper->getSpecialPrice($product)
                ),
            'formated_special_price' => $this->when(
                    $this->productPriceHelper->haveSpecialPrice($product),
                    core()->currency($this->productPriceHelper->getSpecialPrice($product))
                ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}