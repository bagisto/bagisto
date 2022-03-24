<?php

namespace Webkul\Admin\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\User\Notifications\AdminUpdatePassword;
use Webkul\Customer\Notifications\CustomerUpdatePassword;

class PasswordChange
{
    /**
     * Send mail on updating password.
     *
     * @param  \Webkul\Customer\Models\Customer|\Webkul\User\Models\Admin  $adminOrCustomer
     * @return void
     */
    public function sendUpdatePasswordMail($adminOrCustomer)
    {
        try {
            if ($adminOrCustomer instanceof \Webkul\Customer\Models\Customer) {
                Mail::queue(new CustomerUpdatePassword($adminOrCustomer));
            }

            if ($adminOrCustomer instanceof \Webkul\User\Models\Admin) {
                Mail::queue(new AdminUpdatePassword($adminOrCustomer));
            }
        } catch (\Exception $e) {
            report($e);
        }
    }
}