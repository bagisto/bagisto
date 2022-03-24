<?php

namespace Webkul\User\Repositories;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;

class RoleRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return \Webkul\User\Contracts\Role::class;
    }

    /**
     * Create.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        Event::dispatch('user.role.create.before');

        $role = parent::create($attributes);

        Event::dispatch('user.role.create.after', $role);

        return $role;
    }

    /**
     * Update method.
     *
     * @param  array  $data
     * @param  int  $id
     * @return \Webkul\User\Contracts\Role
     */
    public function update(array $data, $id)
    {
        Event::dispatch('user.role.update.before', $id);

        /* making collection for ease */
        $requestedData = collect($data);

        /* updating role data */
        $role = $this->find($id);
        $role->name = $requestedData['name'];
        $role->description = $requestedData['description'];
        $role->permission_type = $requestedData['permission_type'];
        $role->permissions = $requestedData->has('permissions') ? $requestedData['permissions'] : [];
        $role->update();

        Event::dispatch('user.role.update.after', $role);

        return $role;
    }

    /**
     * Delete.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id)
    {
        Event::dispatch('user.role.delete.before', $id);

        parent::delete($id);

        Event::dispatch('user.role.delete.after', $id);
    }
}
