<?php

namespace Webkul\Admin\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\User\Notifications\AdminUpdatePassword;
use Webkul\Customer\Notifications\CustomerUpdatePassword;
use Webkul\Customer\Models\Customer;
use Webkul\User\Models\Admin;

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
            if ($adminOrCustomer instanceof Customer) {
                Mail::queue(new CustomerUpdatePassword($adminOrCustomer));
            } elseif ($adminOrCustomer instanceof Admin) {
                Mail::queue(new AdminUpdatePassword($adminOrCustomer));
            }
        } catch (\Exception $e) {
            report($e);
        }
    }
}