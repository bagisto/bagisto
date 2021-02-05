<?php

namespace Webkul\Product\Facades;

use Illuminate\Support\Facades\Facade;

class ProductImage extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'productimage';
    }
}