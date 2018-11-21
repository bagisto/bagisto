<?php

namespace Webkul\Customer\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Webkul\Customer\Models\CustomerGroup;
use Webkul\Checkout\Models\Cart;
use Webkul\Sales\Models\Order;
use Webkul\Customer\Models\Wishlist;
use Webkul\Customer\Notifications\CustomerResetPassword;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $table = 'customers';

    protected $fillable = ['first_name', 'channel_id', 'last_name', 'gender', 'date_of_birth', 'email', 'password', 'customer_group_id', 'subscribed_to_news_letter'];

    protected $hidden = ['password', 'remember_token'];

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

    /**
    * Send the password reset notification.
    *
    * @param  string  $token
    * @return void
    */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomerResetPassword($token));
    }

    /**
     * Get the customer address that owns the customer.
     */
    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }

    /**
     * Get default customer address that owns the customer.
     */
    public function default_address()
    {
        return $this->hasOne(CustomerAddress::class, 'customer_id')->where('default_address', 1);
    }

    /**
     * Customer's relation with wishlist items
     */
    public function wishlist_items() {
        return $this->hasMany(Wishlist::class, 'customer_id');
    }

    /**
     * get all cart inactive cart instance of a customer
     */
    public function all_carts() {
        return $this->hasMany(Cart::class, 'customer_id');
    }

    /**
     * get inactive cart inactive cart instance of a customer
     */
    public function inactive_carts() {
        return $this->hasMany(Cart::class, 'customer_id')->where('is_active', 0);
    }

    /**
     * get active cart inactive cart instance of a customer
     */
    public function active_carts() {
        return $this->hasMany(Cart::class, 'customer_id')->where('is_active', 1);
    }
}
