<?php

namespace Webkul\Payment\Facades;

use Illuminate\Support\Facades\Facade;

class Payment extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'payment';
    }
}