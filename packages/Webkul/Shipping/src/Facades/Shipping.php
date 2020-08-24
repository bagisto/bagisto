<?php

namespace Webkul\Shipping\Facades;

use Illuminate\Support\Facades\Facade;

class Shipping extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'shipping';
    }
}