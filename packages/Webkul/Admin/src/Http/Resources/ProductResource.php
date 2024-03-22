<?php

namespace Webkul\Admin\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'id'                  => $this->id,
            'type'                => $this->type,
            'sku'                 => $this->sku,
            'name'                => $this->name,
            'price'               => $this->price,
            'formatted_price'     => core()->formatPrice($this->price),
            'images'              => $this->images,
            'inventories'         => $this->inventories,
            'is_options_required' => ! $this->getTypeInstance()->canBeAddedToCartWithoutOptions(),
        ];
    }
}
