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
            'url_path'     => $this->url_path,
            'status'       => $this->status,
            'position'     => $this->position,
            'display_mode' => $this->display_mode,
            'description'  => $this->description,
            'images'       => [
                'banner_url' => $this->banner_url,
                'logo_url'   => $this->logo_url,
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
