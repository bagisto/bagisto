<?php

use Webkul\Shipping\Contracts\Carrier;

class FlatRate extends Carrier 
{
    public function calculateRates()
    {
        return [
            'code' => 'flatrate',
            'title' => 'Flatrate',
            'rates' => [
                [
                    'title' => 'Flat Rate',
                    'rate' => 10
                ]
            ]
        ];
    }
}
?>