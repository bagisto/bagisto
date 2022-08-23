<?php

namespace Webkul\User\Repositories;

use Webkul\Core\Eloquent\Repository;

class RoleRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return 'Webkul\User\Contracts\Role';
    }
}
