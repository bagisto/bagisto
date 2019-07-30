<?php

namespace Webkul\PreOrder;

use Illuminate\Support\Facades\Config;
use Webkul\Checkout\Facades\Cart;
use Webkul\Shipping\Shipping as BaseShipping;
use Webkul\Checkout\Models\CartShippingRate;

/**
 * Class Shipping.
 *
 */
class Shipping extends BaseShipping
{
    /**
     * Rates
     *
     * @var array
     */
    protected $rates = [];

    /**
     * Collects rate from available shipping methods
     *
     * @return array
     */
    public function collectRates()
    {
        if (! $cart = Cart::getCart())
            return false;

        $havePreOrderPaymentItem = false;

        foreach ($cart->items()->get() as $item) {
            if (isset($item->additional['pre_order_payment']))
                $havePreOrderPaymentItem = true;
        }

        if (! $havePreOrderPaymentItem)
            return parent::collectRates();

        $object = new CartShippingRate;

        $object->carrier = 'free';
        $object->carrier_title = core()->getConfigData('sales.carriers.free.title');
        $object->method = 'free_free';
        $object->method_title = core()->getConfigData('sales.carriers.free.title');
        $object->method_description = core()->getConfigData('sales.carriers.free.description');
        $object->price = 0;
        $object->base_price = 0;

        $this->rates[] = $object;

        $this->saveAllShippingRates();

        return [
                'jump_to_section' => 'shipping',
                'html' => view('shop::checkout.onepage.shipping', ['shippingRateGroups' => $this->getGroupedAllShippingRates()])->render()
            ];
    }
}