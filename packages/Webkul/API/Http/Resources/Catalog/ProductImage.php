<?php

namespace Webkul\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductImage extends JsonResource
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
            'id' => $this->id,
            'path' => $this->path,
            'url' => $this->url,
            'original_image_url' => $this->url,
            'small_image_url' => url('cache/small/' . $this->path),
            'medium_image_url' => url('cache/medium/' . $this->path),
            'large_image_url' => url('cache/large/' . $this->path)
        ];
    }
}