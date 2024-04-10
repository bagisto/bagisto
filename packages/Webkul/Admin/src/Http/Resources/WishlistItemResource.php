<?php

namespace Webkul\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WishlistItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'additional' => (object) $this->resource->additional ?? [],
            'product'    => new ProductResource($this->product),
        ];
    }
}
