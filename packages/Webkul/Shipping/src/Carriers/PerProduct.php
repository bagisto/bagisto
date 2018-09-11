<?php

use Webkul\Shipping\Contracts\Carrier;

class PerProduct extends Carrier 
{
    public function calculeRates()
    {
        return [
            'code' => 'perproduct',
            'title' => 'Per Product',
            'rates' => [
                [
                    'title' => 'Per Product',
                    'rate' => 10
                ]
            ]
        ];
    }
}
?>