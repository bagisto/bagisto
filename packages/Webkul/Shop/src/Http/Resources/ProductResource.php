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

        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'sku'         => $this->sku,
            'url_key'     => $this->url_key,
            'is_new'      => $this->new,
            'is_featured' => $this->featured,
            'status'      => $this->status,
            'prices'      => $productTypeInstance->getProductPrices(),
            'base_image'  => product_image()->getProductBaseImage($this),
            'images'      => product_image()->getGalleryImages($this),
        ];
    }
}
