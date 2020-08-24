<?php

namespace Webkul\Velocity\Facades;

use Illuminate\Support\Facades\Facade;

class Velocity extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'velocity';
    }
}