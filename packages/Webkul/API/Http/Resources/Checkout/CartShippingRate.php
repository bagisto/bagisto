<?php

namespace Webkul\API\Http\Resources\Checkout;

use Illuminate\Http\Resources\Json\JsonResource;
use Cart;

class CartShippingRate extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $cart = Cart::getCart();

        return [
            'id'                  => $this->id,
            'carrier'             => $this->carrier,
            'carrier_title'       => $this->carrier_title,
            'method'              => $this->method,
            'method_title'        => $this->method_title,
            'method_description'  => $this->method_description,
            'price'               => $this->price,
            'formated_price'      => core()->formatPrice($this->price, $cart->cart_currency_code),
            'base_price'          => $this->base_price,
            'formated_base_price' => core()->formatBasePrice($this->base_price),
            'created_at'          => $this->created_at,
            'updated_at'          => $this->updated_at
        ];
    }
}