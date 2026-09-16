<?php

namespace Webkul\Product\Repositories;

use Webkul\Product\Contracts\ProductVideo;

class ProductVideoRepository extends ProductMediaRepository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return ProductVideo::class;
    }
}
