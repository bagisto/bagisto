<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
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
            'options' => array_values($this->resource->additional['attributes'] ?? []),
            'product' => new ProductResource($this->product),
        ];

    }
}
