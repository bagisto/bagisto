<?php

namespace Webkul\Velocity\Repositories;

use Prettus\Repository\Traits\CacheableRepository;
use Webkul\Core\Eloquent\Repository;

/**
 * OrderBrands Repository
 *
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderBrandsRepository extends Repository
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Velocity\Contracts\OrderBrand';
    }

}
