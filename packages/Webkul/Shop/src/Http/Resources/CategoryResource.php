<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return  [
            'id'                 => $this->id,
            'name'               => $this->name,
            'slug'               => $this->slug,
            'url_path'           => Storage::url($this->url_path),
            'parent_id'          => $this->parent_id,
            'status'             => $this->status,
            'description'        => $this->description,
            'banner_url'         => $this->banner_url,
            'additional'         => $this->additional,
            'category_icon_url'  => $this->category_icon_url,
            'display_mode'       => $this->display_mode,
            'image'              => Storage::url($this->image),
            'meta_title'         => $this->meta_title,
            'meta_keywords'      => $this->meta_keywords,
            'meta_description'   => $this->meta_description,
            'position'           => $this->position,
            'translations'       => $this->translations,
        ];
    }
}
