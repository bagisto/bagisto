<?php

namespace App\Shipping\Helpers;


use Webkul\Shipping\Contracts\ShippingInterface;

/**
 * Class Rate.
 *
 * @package namespace App\Criteria;
 */
class Rate implements ShippingInterface
{

    public function calculate()
    {
        return 'gg';
    }
}

