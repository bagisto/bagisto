<?php

namespace Webkul\Core\Facades;

use Illuminate\Support\Facades\Facade;

class Menu extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'menu';
    }
}
