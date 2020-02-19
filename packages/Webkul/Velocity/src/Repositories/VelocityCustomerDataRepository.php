<?php

namespace Webkul\Velocity\Repositories;

use Webkul\Core\Eloquent\Repository;

class VelocityCustomerDataRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Velocity\Models\VelocityCustomerData';
    }
}