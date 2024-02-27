<?php

namespace Webkul\Shop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\Tax\Helpers\Tax;

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
            'id'                             => $this->id,
            'is_guest'                       => $this->is_guest,
            'customer_id'                    => $this->customer_id,
            'items_count'                    => $this->items_count,
            'items_qty'                      => $this->items_qty,
            'grand_total'                    => $this->grand_total,
            'base_sub_total'                 => core()->currency($this->base_sub_total),
            'base_tax_total'                 => $this->base_tax_total,
            'base_tax_amounts'               => $taxes,
            'formatted_base_discount_amount' => core()->currency($this->base_discount_amount),
            'base_discount_amount'           => $this->base_discount_amount,
            'base_grand_total'               => core()->currency($this->base_grand_total),
            'selected_shipping_rate'         => core()->currency($this->selected_shipping_rate->base_price ?? 0),
            'coupon_code'                    => $this->coupon_code,
            'selected_shipping_rate_method'  => $this->selected_shipping_rate->method_title ?? '',
            'formatted_grand_total'          => core()->formatPrice($this->grand_total),
            'sub_total'                      => $this->sub_total,
            'formatted_sub_total'            => core()->formatPrice($this->sub_total),
            'tax_total'                      => $this->tax_total,
            'formatted_tax_total'            => core()->formatPrice($this->tax_total),
            'discount_amount'                => $this->discount_amount,
            'formatted_discount_amount'      => core()->formatPrice($this->discount_amount),
            'items'                          => CartItemResource::collection($this->items),
            'billing_address'                => $this->billing_address,
            'shipping_address'               => $this->shipping_address,
            'have_stockable_items'           => $this->haveStockableItems(),
            'payment_method'                 => $this->payment ? core()->getConfigData('sales.payment_methods.'.$this->payment->method.'.title') : '',
        ];
    }
}
