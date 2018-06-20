<?php

namespace Webkul\User;

class Bouncer
{
    /**
     * Checks if user allowed or not for certain action
     *
     * @param  String $permission
     * @return Void
     */
    public static function allow($permission)
    {
        if(!auth()->check() || !auth()->user()->hasPermission($permission))
            abort(401, 'This action is unauthorized.');
    }
}