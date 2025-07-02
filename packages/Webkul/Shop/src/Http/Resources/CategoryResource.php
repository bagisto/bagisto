<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'id'           => $this->id,
            'parent_id'    => $this->parent_id,
            'name'         => $this->name,
            'slug'         => $this->slug,
            'status'       => $this->status,
            'position'     => $this->position,
            'display_mode' => $this->display_mode,
            'description'  => $this->description,
            'logo'         => $this->when($this->logo_path, [
                'small_image_url'    => url('cache/small/'.$this->logo_path),
                'medium_image_url'   => url('cache/medium/'.$this->logo_path),
                'large_image_url'    => url('cache/large/'.$this->logo_path),
                'original_image_url' => url('cache/original/'.$this->logo_path),
            ]),
            'banner'       => $this->when($this->banner_path, [
                'small_image_url'    => url('cache/small/'.$this->banner_path),
                'medium_image_url'   => url('cache/medium/'.$this->banner_path),
                'large_image_url'    => url('cache/large/'.$this->banner_path),
                'original_image_url' => url('cache/original/'.$this->banner_path),
            ]),
            'meta'         => [
                'title'       => $this->meta_title,
                'keywords'    => $this->meta_keywords,
                'description' => $this->meta_description,
            ],
            'translations' => $this->translations,
            'additional'   => $this->additional,
        ];
    }
}
