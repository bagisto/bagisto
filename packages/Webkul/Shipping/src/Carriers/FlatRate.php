<?php

namespace Webkul\Shipping\Carriers;

use Config;
use Webkul\Cart\Models\CartShipping;

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

    public function calculate()
    {
        return [
            'carrier_code' => 'flatrate',
            'carrier_title' => 'Flat Rate',
            'carrier_description' => '',
            'rates' => [
                [
                    'method' => 'flatrate_flatrate',
                    'method_title' => 'Flat Rate',
                    'price' => 10,
                    'price_formated' => core()->currency(10),
                ]
            ]
        ];
        
        $object = new CartShipping;

        $object->carrier = 'flatrate_flatrate';
        $object->carrier_title = $this->getConfigData('description');
        $object->method = 'flatrate';
        $object->method_title = $this->getConfigData('title');
        $object->method_description = $this->getConfigData('description');
        $object->price = 10;

        return [
                'flatrate' => [$object]
            ];
    }
}