<?php

namespace Webkul\CatalogRule\Repositories;

use Webkul\Core\Eloquent\Repository;

class CatalogRuleProductPriceRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\CatalogRule\Contracts\CatalogRuleProductPrice';
    }
}