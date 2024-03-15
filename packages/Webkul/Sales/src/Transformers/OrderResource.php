<?php

namespace Webkul\Sales\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $shippingInformation = [];

        if ($this->haveStockableItems()) {
            $shippingInformation = [
                'shipping_method'               => $this->selected_shipping_rate->method,
                'shipping_title'                => $this->selected_shipping_rate->carrier_title.' - '.$this->selected_shipping_rate->method_title,
                'shipping_description'          => $this->selected_shipping_rate->method_description,
                'shipping_amount'               => $this->selected_shipping_rate->price,
                'base_shipping_amount'          => $this->selected_shipping_rate->base_price,
                'shipping_discount_amount'      => $this->selected_shipping_rate->discount_amount,
                'base_shipping_discount_amount' => $this->selected_shipping_rate->base_discount_amount,

                'shipping_address'              => (new OrderAddressResource($this->shipping_address))->jsonSerialize(),
            ];
        }

        return [
            'cart_id'               => $this->id,
            'is_guest'              => $this->is_guest,
            'customer_id'           => $this->customer_id,
            'customer_type'         => $this->customer ? get_class($this->customer) : null,
            'customer_email'        => $this->customer_email,
            'customer_first_name'   => $this->customer_first_name,
            'customer_last_name'    => $this->customer_last_name,
            'channel_id'            => $this->channel_id,
            'channel_name'          => $this->channel->name,
            'channel_type'          => get_class($this->channel),
            'total_item_count'      => $this->items_count,
            'total_qty_ordered'     => $this->items_qty,
            'base_currency_code'    => $this->base_currency_code,
            'channel_currency_code' => $this->channel_currency_code,
            'order_currency_code'   => $this->cart_currency_code,
            'grand_total'           => $this->grand_total,
            'base_grand_total'      => $this->base_grand_total,
            'sub_total'             => $this->sub_total,
            'base_sub_total'        => $this->base_sub_total,
            'tax_amount'            => $this->tax_total,
            'base_tax_amount'       => $this->base_tax_total,
            'coupon_code'           => $this->coupon_code,
            'applied_cart_rule_ids' => $this->applied_cart_rule_ids,
            'discount_amount'       => $this->discount_amount,
            'base_discount_amount'  => $this->base_discount_amount,
            'billing_address'       => (new OrderAddressResource($this->billing_address))->jsonSerialize(),
            $this->mergeWhen($this->haveStockableItems(), $shippingInformation),
            'payment'               => (new OrderPaymentResource($this->payment))->jsonSerialize(),
            'items'                 => OrderItemResource::collection($this->items)->jsonSerialize(),
        ];
    }
}
