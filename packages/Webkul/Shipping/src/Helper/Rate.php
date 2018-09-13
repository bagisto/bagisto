<?php

namespace Webkul\Shipping\Helper;

use Webkul\Shipping\Carrier\FlatRate;

/**
 * Class Rate.
 *
 */
class Rate extends FlatRate
{
    public function collectRates()
    {
        $data = $this->calculate();
        $rates =[];

        foreach($data as  $rate){
            foreach($rate as $flat){
                $rates[$flat['name']] = $flat['price'];
            }
        }

        return $rates;
    }

}




