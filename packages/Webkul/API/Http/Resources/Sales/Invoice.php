<?php

namespace Webkul\API\Http\Resources\Sales;

use Illuminate\Http\Resources\Json\JsonResource;

class Invoice extends JsonResource
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
            'state' => $this->state,
            'email_sent' => $this->email_sent,
            'total_qty' => $this->total_qty,
            'base_currency_code' => $this->base_currency_code,
            'channel_currency_code' => $this->channel_currency_code,
            'order_currency_code' => $this->order_currency_code,
            'sub_total' => $this->sub_total,
            'base_sub_total' => $this->base_sub_total,
            'grand_total' => $this->grand_total,
            'base_grand_total' => $this->base_grand_total,
            'shipping_amount' => $this->shipping_amount,
            'base_shipping_amount' => $this->base_shipping_amount,
            'tax_amount' => $this->tax_amount,
            'base_tax_amount' => $this->base_tax_amount,
            'discount_amount' => $this->discount_amount,
            'base_discount_amount' => $this->base_discount_amount,
            'order_address' => new OrderAddress($this->address),
            'transaction_id' => $this->transaction_id,
            'items' => InvoiceItem::collection($this->items),
        ];
    }
}