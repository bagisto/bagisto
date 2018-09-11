<?php

use Illuminate\Support\Facades\Config;

class Rates {

    public function collectRates()
    {
        $rates = [];

        foreach(Config::get('carriers') as $shippingMethod) {
            if(isset($shippingMethod['class'])) {
                $object = new $shippingMethod['class'];

                if($rate = $object->calculeRates()) {
                    array_push($rates, $rate);
                }
            }
        }

        return $rates;
    }
    
}

?>