<?php

namespace Webkul\API\Http\Resources\Sales;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItem extends JsonResource
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
            'name' => $this->name,
            // 'product' => $this->when($this->product, new ProductResource($this->product)),
            'description' => $this->description,
            'sku' => $this->sku,
            'description' => $this->description,
            'qty' => $this->qty,
            'price' => $this->price,
            'formated_price' => core()->formatPrice($this->price, $this->invoice->order_currency_code),
            'base_price' => $this->base_price,
            'formated_base_price' => core()->formatBasePrice($this->base_price),
            'total' => $this->total,
            'formated_total' => core()->formatPrice($this->total, $this->invoice->order_currency_code),
            'base_total' => $this->base_total,
            'formated_base_total' => core()->formatBasePrice($this->base_total),
            'tax_amount' => $this->tax_amount,
            'formated_tax_amount' => core()->formatPrice($this->tax_amount, $this->invoice->order_currency_code),
            'base_tax_amount' => $this->base_tax_amount,
            'formated_base_tax_amount' => core()->formatBasePrice($this->base_tax_amount),
            'grand_total' => $this->total + $this->tax_amount,
            'formated_grand_total' => core()->formatPrice($this->total + $this->tax_amount, $this->invoice->order_currency_code),
            'base_grand_total' => $this->base_total + $this->base_tax_amount,
            'formated_base_grand_total' => core()->formatBasePrice($this->base_total + $this->base_tax_amount),
            'additional' => is_array($this->resource->additional)
                    ? $this->resource->additional
                    : json_decode($this->resource->additional, true),
            'child' => new self($this->child)
        ];
    }
}