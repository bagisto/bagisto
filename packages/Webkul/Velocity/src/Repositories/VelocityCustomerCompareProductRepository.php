<?php

namespace Webkul\Velocity\Repositories;

use Webkul\Core\Eloquent\Repository;

class VelocityCustomerCompareProductRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Velocity\Models\VelocityCustomerCompareProduct';
    }
}