<?php

namespace Webkul\CatalogRule\Repositories;

use Webkul\CatalogRule\Contracts\CatalogRuleProductPrice;
use Webkul\Core\Eloquent\Repository;

class CatalogRuleProductPriceRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return CatalogRuleProductPrice::class;
    }
}
