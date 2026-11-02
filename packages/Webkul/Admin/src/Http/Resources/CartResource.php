<?php

namespace Webkul\Admin\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Tax\Facades\Tax;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $taxes = collect(Tax::getTaxRatesWithAmount($this, true))->map(function ($rate) {
            return core()->formatBasePrice($rate ?? 0);
        });

        return [
            'id' => $this->id,
            'is_guest' => $this->is_guest,
            'customer_id' => $this->customer_id,
            'items_count' => $this->items_count,
            'items_qty' => $this->items_qty,
            'sub_total' => $this->base_sub_total,
            'formatted_sub_total' => core()->formatBasePrice($this->base_sub_total),
            'sub_total_incl_tax' => $this->base_sub_total_incl_tax,
            'formatted_sub_total_incl_tax' => core()->formatBasePrice($this->base_sub_total_incl_tax),
            'shipping_method' => $this->shipping_method,
            'shipping_amount' => $this->base_shipping_amount,
            'formatted_shipping_amount' => core()->formatBasePrice($this->base_shipping_amount),
            'shipping_amount_incl_tax' => $this->base_shipping_amount_incl_tax,
            'formatted_shipping_amount_incl_tax' => core()->formatBasePrice($this->base_shipping_amount_incl_tax),
            'tax_total' => $this->base_tax_total,
            'formatted_tax_total' => core()->formatBasePrice($this->base_tax_total),
            'applied_taxes' => $taxes,
            'coupon_code' => $this->coupon_code,
            'discount_amount' => $this->base_discount_amount,
            'formatted_discount_amount' => core()->formatBasePrice($this->base_discount_amount),
            'grand_total' => $this->base_grand_total,
            'formatted_grand_total' => core()->formatBasePrice($this->base_grand_total),
            'items' => CartItemResource::collection($this->items),
            'billing_address' => new AddressResource($this->billing_address),
            'shipping_address' => new AddressResource($this->shipping_address),
            'have_stockable_items' => $this->haveStockableItems(),
            'payment_method' => $this->payment?->method,
            'payment_method_title' => core()->getConfigData('sales.payment_methods.'.$this->payment?->method.'.title'),
        ];
    }
}
