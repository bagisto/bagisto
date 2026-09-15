<?php

namespace Webkul\User;

use Illuminate\Support\Collection;
use Webkul\Core\Acl\AclItem;
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
     * Get the permission tree limited to the permissions the signed-in admin holds, and so may grant.
     */
    public function getGrantableAclItems(): Collection
    {
        $role = auth()->guard('admin')->user()?->role;

        if ($role?->permission_type === 'all') {
            return acl()->getItems();
        }

        return $this->filterAclItems(acl()->getItems(), (array) $role?->permissions);
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

    /**
     * Keep the permission tree items, and their children, found in the given permissions.
     */
    protected function filterAclItems(Collection $items, array $permissions): Collection
    {
        return $items
            ->filter(fn (AclItem $item) => in_array($item->key, $permissions))
            ->map(fn (AclItem $item) => new AclItem(
                key: $item->key,
                name: $item->name,
                route: $item->route,
                sort: $item->sort,
                children: $this->filterAclItems($item->children, $permissions),
            ));
    }
}
