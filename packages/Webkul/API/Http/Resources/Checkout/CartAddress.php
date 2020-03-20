<?php

namespace Webkul\API\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;

class CartAddress extends JsonResource
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
            'first_name'   => $this->first_name,
            'last_name'    => $this->last_name,
            'name'         => $this->name,
            'email'        => $this->email,
            'address1'     => explode(PHP_EOL, $this->address1),
            'country'      => $this->country,
            'country_name' => core()->country_name($this->country),
            'state'        => $this->state,
            'city'         => $this->city,
            'postcode'     => $this->postcode,
            'phone'        => $this->phone,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}