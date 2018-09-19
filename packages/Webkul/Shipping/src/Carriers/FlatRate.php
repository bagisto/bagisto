<?php

namespace Webkul\Shipping\Carriers;

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
        return [];
    }
}