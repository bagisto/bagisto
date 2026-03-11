<?php

namespace Webkul\Sales\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'method' => $this->method,
            'method_title' => $this->method_title,
            'additional' => $request->input('orderData'),
        ];
    }
}
