<?php

namespace Webkul\API\Http\Resources\Sales;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentItem extends JsonResource
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
            'id'                  => $this->id,
            'name'                => $this->name,
            'description'         => $this->description,
            'sku'                 => $this->sku,
            'qty'                 => $this->qty,
            'weight'              => $this->weight,
            'price'               => $this->price,
            'formated_price'      => core()->formatPrice($this->price, $this->shipment->order->order_currency_code),
            'base_price'          => $this->base_price,
            'formated_base_price' => core()->formatBasePrice($this->base_price),
            'total'               => $this->total,
            'formated_total'      => core()->formatPrice($this->total, $this->shipment->order->order_currency_code),
            'base_total'          => $this->base_total,
            'formated_base_total' => core()->formatBasePrice($this->base_total),
            'additional'          => is_array($this->resource->additional)
                                        ? $this->resource->additional
                                        : json_decode($this->resource->additional, true)
        ];
    }
}