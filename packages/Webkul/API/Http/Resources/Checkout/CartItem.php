<?php

namespace Webkul\API\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\API\Http\Resources\Catalog\Product as ProductResource;

class CartItem extends JsonResource
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
            'quantity' => $this->quantity,
            'sku' => $this->sku,
            'type' => $this->type,
            'name' => $this->name,
            'coupon_code' => $this->coupon_code,
            'weight' => $this->weight,
            'total_weight' => $this->total_weight,
            'base_total_weight' => $this->base_total_weight,
            'price' => $this->price,
            'formated_price' => core()->formatPrice($this->price, $this->cart->cart_currency_code),
            'base_price' => $this->base_price,
            'formated_base_price' => core()->formatBasePrice($this->base_price),
            'custom_price' => $this->custom_price,
            'formated_custom_price' => core()->formatPrice($this->custom_price, $this->cart->cart_currency_code),
            'total' => $this->total,
            'formated_total' => core()->formatPrice($this->total, $this->cart->cart_currency_code),
            'base_total' => $this->base_total,
            'formated_base_total' => core()->formatBasePrice($this->base_total),
            'tax_percent' => $this->tax_percent,
            'tax_amount' => $this->tax_amount,
            'formated_tax_amount' => core()->formatPrice($this->tax_amount, $this->cart->cart_currency_code),
            'base_tax_amount' => $this->base_tax_amount,
            'formated_base_tax_amount' => core()->formatBasePrice($this->base_tax_amount),
            'discount_percent' => $this->discount_percent,
            'discount_amount' => $this->discount_amount,
            'formated_discount_amount' => core()->formatPrice($this->discount_amount, $this->cart->cart_currency_code),
            'base_discount_amount' => $this->base_discount_amount,
            'formated_base_discount_amount' => core()->formatBasePrice($this->base_discount_amount),
            'additional' => is_array($this->resource->additional)
                    ? $this->resource->additional
                    : json_decode($this->resource->additional, true),
            'child' => new self($this->child),
            'product' => $this->when($this->product_id, new ProductResource($this->product)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}