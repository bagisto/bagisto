<?php

namespace Webkul\Admin\Listeners;

use Webkul\Admin\Mail\Admin\ResetPasswordNotification;

class Admin
{
    /**
     * Send mail on updating password.
     *
     * @param  \Webkul\User\Models\Admin  $admin
     * @return void
     */
    public function afterPasswordUpdated($admin) {}

    /**
     * Send the admin password reset link.
     *
     * @param  \Webkul\User\Contracts\Admin  $admin
     * @param  string  $token
     * @return void
     */
    public function sendResetLink($admin, $token)
    {
        try {
            $admin->notify(new ResetPasswordNotification($token));
        } catch (\Exception $e) {
            report($e);
        }
    }
}
