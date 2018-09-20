<?php

namespace Webkul\Shipping;

use Illuminate\Support\Facades\Config;

/**
 * Class Shipping.
 *
 */
class Shipping
{
    public function collectRates()
    {
        $rates = [];

        foreach(Config::get('carriers') as $shippingMethod) {
            $object = new $shippingMethod['class'];

            if($object->isAvailable()) {
                $rates[] = $object->calculate();
            }
        }

        return $rates;

        // return view('shop::checkout.onepage.shipping', compact('rates'));
    }
}