<?php

namespace Webkul\Shipping;

use Illuminate\Support\Facades\Config;
use Webkul\Checkout\Facades\Cart;

/**
 * Class Shipping.
 *
 */
class Shipping
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
        if (! $cart = Cart::getCart()) {
            return false;
        }

        $this->removeAllShippingRates();

        foreach (Config::get('carriers') as $shippingMethod) {
            $object = new $shippingMethod['class'];

            if ($rates = $object->calculate()) {
                if (is_array($rates)) {
                    $this->rates = array_merge($this->rates, $rates);
                } else {
                    $this->rates[] = $rates;
                }
            }
        }

        $this->saveAllShippingRates();

        return [
            'jump_to_section' => 'shipping',
            'shippingMethods' => $this->getGroupedAllShippingRates(),
            'html'            => view('shop::checkout.onepage.shipping', ['shippingRateGroups' => $this->getGroupedAllShippingRates()])->render(),
        ];
    }

    /**
     * Persist shipping rate to database
     *
     * @return void
     */
    public function removeAllShippingRates()
    {
        if (! $cart = Cart::getCart()) {
            return;
        }

        foreach ($cart->shipping_rates()->get() as $rate) {
            $rate->delete();
        }
    }

    /**
     * Persist shipping rate to database
     *
     * @return void
     */
    public function saveAllShippingRates()
    {
        if (! $cart = Cart::getCart()) {
            return;
        }

        $shippingAddress = $cart->shipping_address;

        if ($shippingAddress) {

            foreach ($this->rates as $rate) {
                $rate->cart_address_id = $shippingAddress->id;

                $rate->save();
            }
        }
    }

    /**
     * Returns shipping rates, grouped by shipping method
     *
     * @return void
     */
    public function getGroupedAllShippingRates()
    {
        $rates = [];

        foreach ($this->rates as $rate) {
            if (! isset($rates[$rate->carrier])) {
                $rates[$rate->carrier] = [
                    'carrier_title' => $rate->carrier_title,
                    'rates'         => []
                ];
            }

            $rates[$rate->carrier]['rates'][] = $rate;
        }

        return $rates;
    }

    /**
     * Returns active shipping methods
     *
     * @return array
     */
    public function getShippingMethods()
    {
        $methods = [];

        foreach (Config::get('carriers') as $shippingMethod) {
            $object = new $shippingMethod['class'];

            if (! $object->isAvailable()) {
                continue;
            }

            $methods[] = [
                'method'       => $object->getCode(),
                'method_title' => $object->getTitle(),
                'description'  => $object->getDescription()
            ];
        }

        return $methods;
    }
}