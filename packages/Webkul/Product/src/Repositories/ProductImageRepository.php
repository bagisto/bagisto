<?php

namespace Webkul\Product\Repositories;

class ProductImageRepository extends ProductMediaRepository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\Product\Contracts\ProductImage';
    }
}
