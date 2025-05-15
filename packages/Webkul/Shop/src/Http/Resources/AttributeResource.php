<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Attribute\Enums\AttributeTypeEnum;

class AttributeResource extends JsonResource
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
            'id'      => $this->id,
            'code'    => $this->code,
            'type'    => $this->type,
            'name'    => $this->name ?? $this->admin_name,
            'options' => $this->type === AttributeTypeEnum::BOOLEAN->value
                ? AttributeTypeEnum::getBooleanOptions()
                : AttributeOptionResource::collection($this->options),
        ];
    }
}
