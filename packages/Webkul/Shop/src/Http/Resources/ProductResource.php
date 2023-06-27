<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $productTypeInstance = $this->getTypeInstance();

        $reviewHelper = app('Webkul\Product\Helpers\Review');

        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'sku'         => $this->sku,
            'url_key'     => $this->url_key,
            'status'      => $this->status,
            'is_new'      => $this->new,
            'is_featured' => $this->featured,
            'color'       => $this->color,
            'size'        => $this->size,
            'description' => $this->description,
            'on_sale'     => $productTypeInstance->haveDiscount(),
            'prices'      => $productTypeInstance->getProductPrices(),
            'price_html'  => $productTypeInstance->getPriceHtml(),
            'base_image'  => product_image()->getProductBaseImage($this),
            'images'      => product_image()->getGalleryImages($this),
            'avg_ratings' => round($reviewHelper->getAverageRating($this)),
            'min_price'   => core()->formatPrice($productTypeInstance->getMinimalPrice()),
        ];
    }
}
