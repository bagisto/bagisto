<?php

namespace Webkul\Product\Repositories;

use Webkul\Product\Contracts\ProductImage;

class ProductImageRepository extends ProductMediaRepository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return ProductImage::class;
    }
}
