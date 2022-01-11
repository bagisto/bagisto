<?php

namespace Webkul\User\Repositories;

use Illuminate\Support\Facades\Event;
use Webkul\Core\Eloquent\Repository;

class AdminRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model(): string
    {
        return \Webkul\User\Contracts\Admin::class;
    }

    /**
     * Create.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        Event::dispatch('user.admin.create.before');

        $admin = parent::create($attributes);

        Event::dispatch('user.admin.create.after', $admin);

        return $admin;
    }

    /**
     * Update.
     *
     * @param  array  $attributes
     * @param  $id
     * @return mixed
     */
    public function update(array $attributes, $id)
    {
        Event::dispatch('user.admin.update.before', $id);

        $admin = parent::update($attributes, $id);

        if (isset($attributes['password']) && $attributes['password']) {
            Event::dispatch('user.admin.update-password', $admin);
        }

        Event::dispatch('user.admin.update.after', $admin);

        return $admin;
    }

    /**
     * Delete.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id)
    {
        Event::dispatch('user.admin.delete.before', $id);

        parent::delete($id);

        Event::dispatch('user.admin.delete.after', $id);
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
            ->where('roles.permission_type', 'all')
            ->get()
            ->count();
    }

    /**
     * Count admins with all access and active status.
     *
     * @return int
     */
    public function countAdminsWithAllAccessAndActiveStatus(): int
    {
        return $this->getModel()
            ->leftJoin('roles', 'admins.role_id', '=', 'roles.id')
            ->where('admins.status', 1)
            ->where('roles.permission_type', 'all')
            ->get()
            ->count();
    }
}
