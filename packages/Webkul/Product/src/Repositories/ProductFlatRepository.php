<?php

namespace Webkul\Product\Repositories;

use Illuminate\Support\Facades\DB;
use Webkul\Attribute\Models\Attribute;
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
