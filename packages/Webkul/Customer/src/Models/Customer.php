<?php

namespace Webkul\Customer\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Webkul\Customer\Models\CustomerGroup;
use Webkul\Sales\Models\Order;

// use Webkul\User\Notifications\AdminResetPassword;

// use Webkul\User\Notifications\AdminResetPassword;


class Customer extends Authenticatable
{
    use Notifiable;

    protected $table = 'customers';

    protected $fillable = ['first_name', 'channel_id', 'last_name', 'gender', 'date_of_birth', 'phone', 'email', 'password', 'customer_group_id', 'subscribed_to_news_letter'];

    protected $hidden = ['password', 'remember_token'];

    protected $with = ['customerGroup'];

     /**
     * Get the customer full name.
     */
    public function getNameAttribute() {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    /**
     * Get the customer group that owns the customer.
     */
    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class);
    }
}
