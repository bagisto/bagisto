<?php

namespace Webkul\API\Http\Resources\Sales;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTransaction extends JsonResource
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
            'transaction_id'  => $this->transaction_id,
            'status'          => $this->status,
            'type'            => $this->type,
            'payment_method'  => $this->payment_method,
            'data'            => $this->data,
            'updated_at'      => $this->updated_at,
            'created_at'      => $this->created_at,
        ];
    }
}