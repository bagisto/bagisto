<?php

namespace Webkul\Product\Facades;

use Illuminate\Support\Facades\Facade;
use Webkul\Product\ProductImage as BaseProductImage;

class ProductImage extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return BaseProductImage::class;
    }
}
