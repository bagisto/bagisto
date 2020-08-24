<?php

namespace Webkul\API\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class InventorySource extends JsonResource
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
            'id'             => $this->id,
            'code'           => $this->code,
            'name'           => $this->name,
            'description'    => $this->description,
            'contact_name'   => $this->contact_name,
            'contact_email'  => $this->contact_email,
            'contact_number' => $this->contact_number,
            'contact_fax'    => $this->contact_fax,
            'country'        => $this->country,
            'state'          => $this->state,
            'city'           => $this->city,
            'street'         => $this->street,
            'postcode'       => $this->postcode,
            'priority'       => $this->priority,
            'latitude'       => $this->latitude,
            'longitude'      => $this->collongitudeongitudeuntry,
            'status'         => $this->status,
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at
        ];
    }
}