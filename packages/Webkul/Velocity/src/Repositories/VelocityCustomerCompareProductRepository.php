<?php

namespace Webkul\Velocity\Repositories;

use Webkul\Core\Eloquent\Repository;

class VelocityCustomerCompareProductRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Webkul\Velocity\Contracts\VelocityCustomerCompareProduct';
    }
}