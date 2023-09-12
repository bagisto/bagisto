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
        return [
            'id'           => $this->id,
            'parent_id'    => $this->parent_id,
            'name'         => $this->name,
            'slug'         => $this->slug,
            'url_path'     => asset($this->slug),
            'status'       => $this->status,
            'position'     => $this->position,
            'display_mode' => $this->display_mode,
            'description'  => $this->description,
            'images'       => [
                'banner_path'  => Storage::url($this->banner_path),
                'logo_path'    => Storage::url($this->logo_path),
            ],
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
