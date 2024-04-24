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
            'id'                       => $this->id,
            'quantity'                 => $this->quantity,
            'type'                     => $this->type,
            'name'                     => $this->name,
            'price_incl_tax'           => $this->price_incl_tax,
            'price_excl_tax'           => $this->price,
            'formatted_price_incl_tax' => core()->formatPrice($this->price_incl_tax),
            'formatted_price_excl_tax' => core()->formatPrice($this->price),
            'total_incl_tax'           => $this->total_incl_tax,
            'total_excl_tax'           => $this->total,
            'formatted_total_incl_tax' => core()->formatPrice($this->total_incl_tax),
            'formatted_total_excl_tax' => core()->formatPrice($this->total),
            'options'                  => array_values($this->resource->additional['attributes'] ?? []),
            'base_image'               => $this->getTypeInstance()->getBaseImage($this),
            'product_url_key'          => $this->product->url_key,
        ];
    }
}
