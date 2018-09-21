<?php

namespace Webkul\Shipping\Carriers;

use Config;
use Webkul\Cart\Models\CartShipping;
use Webkul\Shipping\Facades\Shipping;

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
        if(!$this->isAvailable())
            return false;

        $object = new CartShipping;

        $object->carrier = 'flatrate';
        $object->carrier_title = $this->getConfigData('title');
        $object->method = 'flatrate_flatrate';
        $object->method_title = $this->getConfigData('title');
        $object->method_description = $this->getConfigData('description');
        $object->price = 10;

        return $object;
    }
}