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
        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'sku'     => $this->sku,
            'url_key' => $this->url_key,
            'status'  => $this->status,
            'images'  => product_image()->getGalleryImages($this),
        ];
    }
}
