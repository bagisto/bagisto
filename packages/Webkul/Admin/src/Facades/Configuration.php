<?php

namespace Webkul\Admin\Facades;

use Illuminate\Support\Facades\Facade;

class Configuration extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'configuration';
    }
}
