<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'id'               => $this->id,
            'items_count'      => $this->items_count,
            'items_qty'        => $this->items_qty,
            'grand_total'      => $this->grand_total,
            'sub_total'        => $this->sub_total,
            'tax_total'        => $this->tax_total,
            'discount_amount'  => $this->discount_amount,
            'items'            => CartItemResource::collection($this->items),
        ];
    }
}
