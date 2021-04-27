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

    /**
     * Update method.
     *
     * @param  array  $data
     * @param  int  $id
     *
     * @return \Webkul\User\Model\Role
     */
    public function update(array $data, $id)
    {
        /* making collection for ease */
        $requestedData = collect($data);

        /* updating role data */
        $role = $this->find($id);
        $role->name = $requestedData['name'];
        $role->description = $requestedData['description'];
        $role->permission_type = $requestedData['permission_type'];
        $role->permissions = $requestedData->has('permissions') ? $requestedData['permissions'] : [];
        $role->update();

        /* returning updated role */
        return $role;
    }
}