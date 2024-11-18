<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Tax\Facades\Tax;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $taxes = collect(Tax::getTaxRatesWithAmount($this, true))->map(function ($rate) {
            return core()->currency($rate ?? 0);
        });

        return [
            'id'                                 => $this->id,
            'is_guest'                           => $this->is_guest,
            'customer_id'                        => $this->customer_id,
            'items_count'                        => $this->items_count,
            'items_qty'                          => $this->items_qty,
            'applied_taxes'                      => $taxes,
            'tax_total'                          => $this->tax_total,
            'formatted_tax_total'                => core()->formatPrice($this->tax_total),
            'sub_total_incl_tax'                 => $this->sub_total_incl_tax,
            'sub_total'                          => $this->sub_total,
            'formatted_sub_total_incl_tax'       => core()->formatPrice($this->sub_total_incl_tax),
            'formatted_sub_total'                => core()->formatPrice($this->sub_total),
            'coupon_code'                        => $this->coupon_code,
            'discount_amount'                    => $this->discount_amount,
            'formatted_discount_amount'          => core()->formatPrice($this->discount_amount),
            'shipping_method'                    => $this->shipping_method,
            'shipping_amount'                    => $this->shipping_amount,
            'formatted_shipping_amount'          => core()->formatPrice($this->shipping_amount),
            'shipping_amount_incl_tax'           => $this->shipping_amount_incl_tax,
            'formatted_shipping_amount_incl_tax' => core()->formatPrice($this->shipping_amount_incl_tax),
            'grand_total'                        => $this->grand_total,
            'formatted_grand_total'              => core()->formatPrice($this->grand_total),
            'items'                              => CartItemResource::collection($this->items),
            'billing_address'                    => new AddressResource($this->billing_address),
            'shipping_address'                   => new AddressResource($this->shipping_address),
            'have_stockable_items'               => $this->haveStockableItems(),
            'payment_method'                     => $this->payment?->method,
            'payment_method_title'               => core()->getConfigData('sales.payment_methods.'.$this->payment?->method.'.title'),
        ];
    }
}
