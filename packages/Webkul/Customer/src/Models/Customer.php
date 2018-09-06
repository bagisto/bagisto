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
    protected $fillable = ['first_name','last_name','gender','date_of_birth','phone','email','password'];
    protected $hidden = ['password','remember_token'];

    public function getNameAttribute() {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }
}
