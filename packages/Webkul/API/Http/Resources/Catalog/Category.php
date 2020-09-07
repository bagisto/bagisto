<?php

namespace Webkul\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
            'code'             => $this->code,
            'name'             => $this->name,
            'slug'             => $this->slug,
            'display_mode'     => $this->display_mode,
            'description'      => $this->description,
            'meta_title'       => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords'    => $this->meta_keywords,
            'status'           => $this->status,
            'image_url'        => $this->image_url,
            'additional'       => is_array($this->resource->additional)
                                    ? $this->resource->additional
                                    : json_decode($this->resource->additional, true),
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}