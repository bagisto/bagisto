<?php

namespace Webkul\API\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;

class CartPayment extends JsonResource
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
            'id'           => $this->id,
            'method'       => $this->method,
            'method_title' => core()->getConfigData('sales.paymentmethods.' . $this->method . '.title'),
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at
        ];
    }
}