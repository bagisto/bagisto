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
            'id'       => $this->id,
            'quantity' => $this->quantity,
            'type'     => $this->type,
            'name'     => $this->name,
            'price'    => $this->price,
            'total'    => $this->total,
            'options'  => $this->additional,
        ];
    }
}
