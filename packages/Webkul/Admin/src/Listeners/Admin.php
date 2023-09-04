<?php

namespace Webkul\Admin\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Mail\Admin\UpdatePasswordNotification;

class Admin
{
    /**
     * Send mail on updating password.
     *
     * @param  \Webkul\User\Models\Admin  $admin
     * @return void
     */
    public function afterPasswordUpdated($admin)
    {
        return;
    }
}