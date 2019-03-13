<?php

namespace Webkul\API\Http\Resources\Sales;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItem extends JsonResource
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
            'sku' => $this->sku,
            'type' => $this->type,
            'name' => $this->name,
            'coupon_code' => $this->coupon_code,
            'weight' => $this->weight,
            'total_weight' => $this->total_weight,
            'qty_ordered' => $this->qty_ordered,
            'qty_canceled' => $this->qty_canceled,
            'qty_shipped' => $this->qty_shipped,
            'qty_refunded' => $this->qty_refunded,
            'price' => $this->price,
            'base_price' => $this->base_price,
            'total' => $this->total,
            'base_total' => $this->base_total,
            'total_invoiced' => $this->total_invoiced,
            'base_total_invoiced' => $this->base_total_invoiced,
            'amount_refunded' => $this->amount_refunded,
            'base_amount_refunded' => $this->base_amount_refunded,
            'discount_percent' => $this->discount_percent,
            'discount_amount' => $this->discount_amount,
            'base_discount_amount' => $this->base_discount_amount,
            'discount_invoiced' => $this->discount_invoiced,
            'base_discount_invoiced' => $this->base_discount_invoiced,
            'discount_refunded' => $this->discount_refunded,
            'base_discount_refunded' => $this->base_discount_refunded,
            'tax_percent' => $this->tax_percent,
            'tax_amount' => $this->tax_amount,
            'base_tax_amount' => $this->base_tax_amount,
            'tax_amount_invoiced' => $this->tax_amount_invoiced,
            'base_tax_amount_invoiced' => $this->base_tax_amount_invoiced,
            'tax_amount_refunded' => $this->tax_amount_refunded,
            'base_tax_amount_refunded' => $this->base_tax_amount_refunded,
            'additional' => is_array($this->resource->additional)
                    ? $this->resource->additional
                    : json_decode($this->resource->additional, true),
            'child' => new self($this->child)
        ];
    }
}