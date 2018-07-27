<?php

namespace Webkul\Customer\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

// use Webkul\User\Notifications\AdminResetPassword;

// use Webkul\User\Notifications\AdminResetPassword;


class Customer extends Authenticatable
{
    use Notifiable;

    protected $table = 'customers';
}
