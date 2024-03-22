<?php

namespace Webkul\Sales\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderAddressResource extends JsonResource
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
            'address_type' => $this->address_type,
            'first_name'   => $this->first_name,
            'last_name'    => $this->last_name,
            'gender'       => $this->gender,
            'company_name' => $this->company_name,
            'address'      => $this->address,
            'city'         => $this->city,
            'state'        => $this->state,
            'country'      => $this->country,
            'postcode'     => $this->postcode,
            'email'        => $this->email,
            'phone'        => $this->phone,
            'vat_id'       => $this->vat_id,
        ];
    }
}
