<?php

namespace Webkul\Product\Repositories;

use Webkul\Core\Eloquent\Repository;

class ProductFlatRepository extends Repository
{
    /**
     * Specify model.
     */
    public function model(): string
    {
        return 'Webkul\Product\Contracts\ProductFlat';
    }
}
