<?php

namespace Webkul\API\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;
use Webkul\API\Http\Resources\Core\Channel as ChannelResource;
use Webkul\API\Http\Resources\Customer\Customer as CustomerResource;

class Cart extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $taxes = \Webkul\Tax\Helpers\Tax::getTaxRatesWithAmount($this, false);
        $baseTaxes = \Webkul\Tax\Helpers\Tax::getTaxRatesWithAmount($this, true);

        $formatedTaxes = $this->formatTaxAmounts($taxes, false);
        $formatedBaseTaxes = $this->formatTaxAmounts($baseTaxes, true);

        return [
            'id' => $this->id,
            'customer_email'            => $this->customer_email,
            'customer_first_name'       => $this->customer_first_name,
            'customer_last_name'        => $this->customer_last_name,
            'shipping_method'           => $this->shipping_method,
            'coupon_code'               => $this->coupon_code,
            'is_gift'                   => $this->is_gift,
            'items_count'               => $this->items_count,
            'items_qty'                 => $this->items_qty,
            'exchange_rate'             => $this->exchange_rate,
            'global_currency_code'      => $this->global_currency_code,
            'base_currency_code'        => $this->base_currency_code,
            'channel_currency_code'     => $this->channel_currency_code,
            'cart_currency_code'        => $this->cart_currency_code,
            'grand_total'               => $this->grand_total,
            'formated_grand_total'      => core()->formatPrice($this->grand_total, $this->cart_currency_code),
            'base_grand_total'          => $this->base_grand_total,
            'formated_base_grand_total' => core()->formatBasePrice($this->base_grand_total),
            'sub_total'                 => $this->sub_total,
            'formated_sub_total'        => core()->formatPrice($this->sub_total, $this->cart_currency_code),
            'base_sub_total'            => $this->base_sub_total,
            'formated_base_sub_total'   => core()->formatBasePrice($this->base_sub_total),
            'tax_total'                 => $this->tax_total,
            'formated_tax_total'        => core()->formatPrice($this->tax_total, $this->cart_currency_code),
            'base_tax_total'            => $this->base_tax_total,
            'formated_base_tax_total'   => core()->formatBasePrice($this->base_tax_total),
            'discount'                  => $this->discount_amount,
            'formated_discount'         => core()->formatPrice($this->discount_amount, $this->cart_currency_code),
            'base_discount'             => $this->base_discount_amount,
            'formated_base_discount'    => core()->formatBasePrice($this->base_discount_amount),
            'checkout_method'           => $this->checkout_method,
            'is_guest'                  => $this->is_guest,
            'is_active'                 => $this->is_active,
            'conversion_time'           => $this->conversion_time,
            'customer'                  => $this->when($this->customer_id, new CustomerResource($this->customer)),
            'channel'                   => $this->when($this->channel_id, new ChannelResource($this->channel)),
            'items'                     => CartItem::collection($this->items),
            'selected_shipping_rate'    => new CartShippingRate($this->selected_shipping_rate),
            'payment'                   => new CartPayment($this->payment),
            'billing_address'           => new CartAddress($this->billing_address),
            'shipping_address'          => new CartAddress($this->shipping_address),
            'created_at'                => $this->created_at,
            'updated_at'                => $this->updated_at,
            'taxes'                     => json_encode($taxes, JSON_FORCE_OBJECT),
            'formated_taxes'            => json_encode($formatedTaxes, JSON_FORCE_OBJECT),
            'base_taxes'                => json_encode($baseTaxes, JSON_FORCE_OBJECT),
            'formated_base_taxes'       => json_encode($formatedBaseTaxes, JSON_FORCE_OBJECT),
        ];
    }

    /**
     * @param array $taxes
     * @param bool  $isBase
     *
     * @return array
     */
    private function formatTaxAmounts(array $taxes, bool $isBase = false): array
    {
        $result = [];

        foreach ($taxes as $taxRate => $taxAmount) {
            if ($isBase === true) {
                $result[$taxRate] = core()->formatBasePrice($taxAmount);
            } else {
                $result[$taxRate] = core()->formatPrice($taxAmount, $this->cart_currency_code);
            }
        }

        return $result;
    }
}
