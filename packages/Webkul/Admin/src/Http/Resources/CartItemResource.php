<?php

namespace Webkul\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'id'              => $this->id,
            'cart_id'         => $this->cart_id,
            'product_id'      => $this->product_id,
            'sku'             => $this->sku,
            'quantity'        => $this->quantity,
            'type'            => $this->type,
            'name'            => $this->name,
            'price'           => $this->price,
            'formatted_price' => core()->formatPrice($this->price),
            'total'           => $this->total,
            'formatted_total' => core()->formatPrice($this->total),
            'options'         => array_values($this->resource->additional['attributes'] ?? []),
            'additional'      => (object) $this->resource->additional,
            'product'         => new ProductResource($this->product),
        ];
    }
}
