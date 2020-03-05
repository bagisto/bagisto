<?php

namespace Webkul\User\Repositories;

use Webkul\Core\Eloquent\Repository;

class RoleRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\User\Contracts\Role';
    }
}