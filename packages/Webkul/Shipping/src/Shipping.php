<?php

namespace Webkul\Shipping;

use Illuminate\Support\Facades\Config;
use Webkul\Checkout\Facades\Cart;

class Shipping
{
    /**
     * Rates.
     *
     * @var array
     */
    protected $rates = [];

    /**
     * Collects rate from available shipping methods.
     *
     * @return array|boolean
     */
    public function collectRates()
    {
        if (! Cart::getCart()) {
            return false;
        }

        $this->removeAllShippingRates();

        $ratesList = [];

        foreach (Config::get('carriers') as $shippingMethod) {
            $object = new $shippingMethod['class'];

            if ($rates = $object->calculate()) {
                if (is_array($rates)) {
                    $ratesList[] = $rates;
                } else {
                    $ratesList[] = [$rates];
                }
            }
        }

        $this->rates = array_merge(...$ratesList);

        $this->saveAllShippingRates();

        return [
            'shippingMethods' => $this->getGroupedAllShippingRates(),
        ];
    }

    /**
     * Remove all shipping rates.
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

        $this->rates = [];
    }

    /**
     * Save all shipping rates.
     *
     * @return void
     */
    public function saveAllShippingRates()
    {
        if (! $cart = Cart::getCart()) {
            return;
        }

        $shippingAddress = $cart->shipping_address;

        if (! $shippingAddress) {
            return;
        }

        foreach ($this->rates as $rate) {
            $rate->cart_address_id = $shippingAddress->id;

            $rate->save();
        }
    }

    /**
     * Returns shipping rates, grouped by shipping method.
     *
     * @return array
     */
    public function getGroupedAllShippingRates()
    {
        $rates = [];

        foreach ($this->rates as $rate) {
            if (! isset($rates[$rate->carrier])) {
                $rates[$rate->carrier] = [
                    'carrier_title' => $rate->carrier_title,
                    'rates'         => [],
                ];
            }

            $rate['base_formatted_price'] = core()->currency($rate->base_price);
            $rates[$rate->carrier]['rates'][] = $rate;
        }

        return $rates;
    }

    /**
     * Returns active shipping methods.
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
                'code'         => $object->getCode(),
                'method'       => $object->getMethod(),
                'method_title' => $object->getTitle(),
                'description'  => $object->getDescription(),
            ];
        }

        return $methods;
    }

    /**
     * Is method exist in active shipping methods.
     *
     * @param  string  $shippingMethodCode
     * @return boolean
     */
    public function isMethodCodeExists($shippingMethodCode)
    {
        $shippingMethods = $this->collectRates()['shippingMethods'] ?? [];

        if (
            empty($shippingMethods)
            || ! $shippingMethods
        ) {
            return false;
        }

        foreach ($shippingMethods as $shippingMethod) {
            foreach ($shippingMethod['rates'] as $rate) {
                if ($rate->method === $shippingMethodCode) {
                    return true;
                }
            }
        }

        return false;
    }
}
