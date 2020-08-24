<?php

namespace Webkul\API\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class Customer extends JsonResource
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
            'id'            => $this->id,
            'email'         => $this->email,
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'name'          => $this->name,
            'gender'        => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'phone'         => $this->phone,
            'status'        => $this->status,
            'group'         => $this->when($this->customer_group_id, new CustomerGroup($this->group)),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}