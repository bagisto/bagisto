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
            'id'                            => $this->id,
            'state'                         => $this->state,
            'email_sent'                    => $this->email_sent,
            'total_qty'                     => $this->total_qty,
            'base_currency_code'            => $this->base_currency_code,
            'channel_currency_code'         => $this->channel_currency_code,
            'order_currency_code'           => $this->order_currency_code,
            'sub_total'                     => $this->sub_total,
            'formated_sub_total'            => core()->formatPrice($this->sub_total, $this->order_currency_code),
            'base_sub_total'                => $this->base_sub_total,
            'formated_base_sub_total'       => core()->formatBasePrice($this->base_sub_total),
            'grand_total'                   => $this->grand_total,
            'formated_grand_total'          => core()->formatPrice($this->grand_total, $this->order_currency_code),
            'base_grand_total'              => $this->base_grand_total,
            'formated_base_grand_total'     => core()->formatBasePrice($this->base_grand_total),
            'shipping_amount'               => $this->shipping_amount,
            'formated_shipping_amount'      => core()->formatPrice($this->shipping_amount, $this->order_currency_code),
            'base_shipping_amount'          => $this->base_shipping_amount,
            'formated_base_shipping_amount' => core()->formatBasePrice($this->base_shipping_amount),
            'tax_amount'                    => $this->tax_amount,
            'formated_tax_amount'           => core()->formatPrice($this->tax_amount, $this->order_currency_code),
            'base_tax_amount'               => $this->base_tax_amount,
            'formated_base_tax_amount'      => core()->formatBasePrice($this->base_tax_amount),
            'discount_amount'               => $this->discount_amount,
            'formated_discount_amount'      => core()->formatPrice($this->discount_amount, $this->order_currency_code),
            'base_discount_amount'          => $this->base_discount_amount,
            'formated_base_discount_amount' => core()->formatBasePrice($this->base_discount_amount),
            'order_address'                 => new OrderAddress($this->address),
            'transaction_id'                => $this->transaction_id,
            'items'                         => InvoiceItem::collection($this->items),
            'created_at'                    => $this->created_at
        ];
    }
}