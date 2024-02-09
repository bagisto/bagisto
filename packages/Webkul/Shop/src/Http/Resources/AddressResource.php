<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'id'              => $this->id,
            'address_type'    => $this->address_type,
            'customer_id'     => $this->customer_id,
            'cart_id'         => $this->cart_id,
            'order_id'        => $this->order_id,
            'first_name'      => $this->first_name,
            'last_name'       => $this->last_name,
            'gender'          => $this->gender,
            'company_name'    => $this->company_name,
            'address1'        => $this->address1,
            'address2'        => $this->address2,
            'city'            => $this->city,
            'state'           => $this->state,
            'country'         => $this->country,
            'postcode'        => $this->postcode,
            'email'           => $this->email,
            'phone'           => $this->phone,
            'vat_id'          => $this->vat_id,
            'default_address' => $this->default_address,
            'additional'      => $this->additional,
        ];
    }
}
