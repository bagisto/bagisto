<?php

namespace Webkul\API\Http\Resources\Catalog;

use Illuminate\Http\Resources\Json\JsonResource;

class AttributeFamily extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'status' => $this->status,
            'groups' => AttributeGroup::collection($this->attribute_groups),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}