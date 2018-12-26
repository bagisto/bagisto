<?php

namespace Webkul\Shipping\Carriers;

use Config;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Checkout\Facades\Cart;

/**
 * Class Rate.
 *
 */
class FlatRate extends AbstractShipping
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'flatrate';

    /**
     * Returns rate for flatrate
     *
     * @return array
     */
    public function calculate()
    {
        if(!$this->isAvailable())
            return false;

        $cart = Cart::getCart();

        $object = new CartShippingRate;

        $object->carrier = 'flatrate';
        $object->carrier_title = $this->getConfigData('title');
        $object->method = 'flatrate_flatrate';
        $object->method_title = $this->getConfigData('title');
        $object->method_description = $this->getConfigData('description');

        if ($this->getConfigData('type') == 'per_unit') {
            $object->price = core()->convertPrice($this->getConfigData('default_rate')) * $cart->items_qty;
            $object->base_price = $this->getConfigData('default_rate') * $cart->items_qty;
        } else {
            $object->price = core()->convertPrice($this->getConfigData('default_rate'));
            $object->base_price = $this->getConfigData('default_rate');
        }


        return $object;
    }
}