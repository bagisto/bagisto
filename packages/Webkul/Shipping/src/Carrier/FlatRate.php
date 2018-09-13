<?php

namespace Webkul\Shipping\Carrier;

use Webkul\Shipping\Contracts\AbstractShipping;
use Config;

/**
 * Class Rate.
 *
 */
class FlatRate extends AbstractShipping
{

    public function calculate()
    {
        $all = Config::get('carrier');
        return $all;
    }



}