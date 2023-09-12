<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryTreeResource extends JsonResource
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
            'id'        => $this->id,
            'parent_id' => $this->parent_id,
            'name'      => $this->name,
            'slug'      => $this->slug,
            'url_path'  => $this->url_path,
            'url'       => $this->url,
            'status'    => $this->status,
            'children'  => self::collection($this->children),
        ];
    }
}
