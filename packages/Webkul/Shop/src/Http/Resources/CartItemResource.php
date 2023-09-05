<?php

namespace Webkul\Shop\Http\Resources;

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
            'quantity'        => $this->quantity,
            'type'            => $this->type,
            'name'            => $this->name,
            'price'           => $this->price,
            'formatted_price' => core()->formatPrice($this->price),
            'total'           => $this->total,
            'formatted_total' => core()->formatPrice($this->total),
            'options'         => array_values($this->resource->additional['attributes'] ?? []),
            'base_image'      => $this->getTypeInstance()->getBaseImage($this),
        ];
    }
}
