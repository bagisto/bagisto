<?php

namespace Webkul\Shipping\Carriers;

use Config;
use Webkul\Cart\Models\CartShippingRate;
use Webkul\Shipping\Facades\Shipping;

/**
 * Class Rate.
 *
 */
class Free extends AbstractShipping
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'free';

    /**
     * Returns rate for flatrate
     *
     * @return array
     */
    public function calculate()
    {
        if(!$this->isAvailable())
            return false;

        $object = new CartShippingRate;

        $object->carrier = 'free';
        $object->carrier_title = $this->getConfigData('title');
        $object->method = 'free_free';
        $object->method_title = $this->getConfigData('title');
        $object->method_description = $this->getConfigData('description');
        $object->price = 0;
        $object->base_price = 0;

        return $object;
    }
}