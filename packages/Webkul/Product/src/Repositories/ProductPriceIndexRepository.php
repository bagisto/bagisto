<?php

namespace Webkul\Product\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Contracts\ProductPriceIndex;

class ProductPriceIndexRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ProductPriceIndex::class;
    }
}
