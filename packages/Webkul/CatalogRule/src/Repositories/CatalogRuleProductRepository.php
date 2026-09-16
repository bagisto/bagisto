<?php

namespace Webkul\CatalogRule\Repositories;

use Webkul\CatalogRule\Contracts\CatalogRuleProduct;
use Webkul\Core\Eloquent\Repository;

class CatalogRuleProductRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return CatalogRuleProduct::class;
    }
}
