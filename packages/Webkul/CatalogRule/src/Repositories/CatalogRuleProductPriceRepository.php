<?php

namespace Webkul\CatalogRule\Repositories;

use Webkul\Core\Eloquent\Repository;

class CatalogRuleProductPriceRepository extends Repository
{
    /**
     * Specify Model class name.
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\CatalogRule\Contracts\CatalogRuleProductPrice';
    }
}