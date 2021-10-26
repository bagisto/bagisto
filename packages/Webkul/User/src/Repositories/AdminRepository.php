<?php

namespace Webkul\User\Repositories;

use Webkul\Core\Eloquent\Repository;

class AdminRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return mixed
     */
    function model(): string
    {
        return \Webkul\User\Contracts\Admin::class;
    }

    /**
     * Count admins with all access.
     *
     * @return int
     */
    public function countAdminsWithAllAccess(): int
    {
        return $this->getModel()
            ->leftJoin('roles', 'admins.role_id', '=', 'roles.id')
            ->where(["roles.permission_type" => "all"])
            ->get()
            ->count();
    }
}
