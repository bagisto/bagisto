<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MiniCart extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        // It is just demo content will update the resource
        return [
            'id'      => $this->id,
            'name'    => $this->name,
            'qty'     => $this->qty,
            'color'   => $this->color,
            'size'    => $this->size,
            'images'  => product_image()->getGalleryImages($this),
        ];
    }
}
