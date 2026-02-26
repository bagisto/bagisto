<?php

namespace Webkul\Product\Facades;

use Illuminate\Support\Facades\Facade;
use Webkul\Product\ProductVideo as BaseProductVideo;

class ProductVideo extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return BaseProductVideo::class;
    }
}
