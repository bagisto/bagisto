<?php

namespace Webkul\User;

use Webkul\User\Contracts\Role;

class Bouncer
{
    /**
     * Whether the signed-in admin holds the given permission.
     *
     * @param  string  $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        if (
            auth()->guard('admin')->check()
            && auth()->guard('admin')->user()->role->permission_type == 'all'
        ) {
            return true;
        } else {
            if (
                ! auth()->guard('admin')->check()
                || ! auth()->guard('admin')->user()->hasPermission($permission)
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Whether the signed-in admin may grant the given role, which must hold no permission they lack.
     *
     * @param  Role|null  $role
     */
    public function canGrantRole($role): bool
    {
        return (bool) $role
            && $this->canGrantPermissions($role->permission_type, (array) $role->permissions);
    }

    /**
     * Whether the signed-in admin may grant the given permission type and permissions, holding all of them.
     */
    public function canGrantPermissions(?string $permissionType, array $permissions): bool
    {
        $role = auth()->guard('admin')->user()?->role;

        if (! $role) {
            return false;
        }

        if ($role->permission_type === 'all') {
            return true;
        }

        return $permissionType !== 'all'
            && empty(array_diff($permissions, (array) $role->permissions));
    }

    /**
     * Abort unless the signed-in admin holds the given permission.
     *
     * @param  string  $permission
     * @return void
     */
    public static function allow($permission)
    {
        if (
            ! auth()->guard('admin')->check()
            || ! auth()->guard('admin')->user()->hasPermission($permission)
        ) {
            abort(401, 'This action is unauthorized');
        }
    }
}
