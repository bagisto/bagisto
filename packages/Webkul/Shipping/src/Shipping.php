<?php

namespace Webkul\Shipping;

use Illuminate\Support\Facades\Config;

/**
 * Class Shipping.
 *
 */
class Shipping
{
    protected $rates = [];

    public function collectRates()
    {
        foreach(Config::get('carriers') as $shippingMethod) {
            $object = new $shippingMethod['class'];

            if($rates = $object->calculate()) {
                if(is_array($rates)) {
                    $this->rates = array_merge($this->rates, $rates);
                } else {
                    $this->rates[] = $rates;
                }
            }
        }

        return [
                'jump_to_section' => 'shipping',
                'html' => view('shop::checkout.onepage.shipping', ['shippingRateGroups' => $this->getGroupedAllShippingRates()])->render()
            ];
    }

    public function getGroupedAllShippingRates()
    {
        $rates = [];

        foreach ($this->rates as $rate) {
            if (!isset($rates[$rate->carrier])) {
                $rates[$rate->carrier] = [
                    'carrier_title' => $rate->carrier_title,
                    'rates' => []
                ];
            }

            $rates[$rate->carrier]['rates'][] = $rate;
        }

        return $rates;
    }
}