<?php

namespace Webkul\Tax\Facades;

use Illuminate\Support\Facades\Facade;
use Webkul\Tax\Tax as BaseTax;

class Tax extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return BaseTax::class;
    }
}
