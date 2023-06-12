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
            'id'             =>  $this->id,
            'price'          =>  $this->product->price,
            'color'          =>  $this->product->color,
            'name'           =>  $this->product->name,
            'base_image_url' => $this->product->base_image_url,     
            'product'        =>  $this->product()->first(),
        ];
    }
}
