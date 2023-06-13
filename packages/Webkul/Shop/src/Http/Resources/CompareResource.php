<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompareResource extends JsonResource
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
            'id'           => $this->id,
            'product_id'   => $this->items_count,
            'customer_id'  => $this->items_qty,
            // Will create with resource
            'product'      => new ProductResource($this->product),
        ];
    }
}
