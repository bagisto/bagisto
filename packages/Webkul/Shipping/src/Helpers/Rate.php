<?php

namespace Webkul\Shipping\Helper;

use Illuminate\Support\Facades\Config;

/**
 * Class Rate.
 *
 */
class Rate
{
    public function collectRates()
    {
        $rates = [];

        $shippingMethods = Config::get('carriers');

        foreach($shippingMethods as $shippingMethod) {
            $object = new $shippingMethod['class'];

            if($rate = $object->calculate()) {
                $rates[] = $rate;
            }
        }

        return $rates;
    }
}