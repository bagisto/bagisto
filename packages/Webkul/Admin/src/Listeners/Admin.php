<?php

namespace Webkul\Admin\Listeners;

use Illuminate\Support\Facades\Mail;

class Admin
{
    /**
     * Send mail on updating password.
     *
     * @param  \Webkul\User\Models\Admin  $admin
     * @return void
     */
    public function afterPasswordUpdated($admin) {}
}
