<?php

namespace Webkul\User;

class Bouncer
{
    /**
     * Checks if user allowed or not for certain action
     *
     * @param  string  $permission
     * @return void
     */
    public function hasPermission($permission)
    {
        if (auth()->guard('admin')->check() && auth()->guard('admin')->user()->role->permission_type == 'all') {
            return true;
        } else {
            if (! auth()->guard('admin')->check() || ! auth()->guard('admin')->user()->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if user allowed or not for certain action
     *
     * @param  string  $permission
     * @return void
     */
    public static function allow($permission)
    {
        if (! auth()->guard('admin')->check() || ! auth()->guard('admin')->user()->hasPermission($permission)) {
            abort(401, 'This action is unauthorized');
        }
    }
}