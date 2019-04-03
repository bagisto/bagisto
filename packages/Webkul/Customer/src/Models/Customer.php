<?php

namespace Webkul\Customer\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Webkul\Checkout\Models\CartProxy;
use Webkul\Sales\Models\OrderProxy;
use Webkul\Product\Models\ProductReviewProxy;
use Webkul\Customer\Notifications\CustomerResetPassword;
use Webkul\Customer\Contracts\Customer as CustomerContract;

class Customer extends Authenticatable implements CustomerContract
{
    use Notifiable;

    protected $table = 'customers';

    protected $fillable = ['first_name', 'channel_id', 'last_name', 'gender', 'date_of_birth', 'email', 'password', 'customer_group_id', 'subscribed_to_news_letter', 'is_verified', 'token'];

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
    public function group()
    {
        return $this->belongsTo(CustomerGroupProxy::modelClass(), 'customer_group_id');
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
        return $this->hasMany(CustomerAddressProxy::modelClass(), 'customer_id');
    }

    /**
     * Get default customer address that owns the customer.
     */
    public function default_address()
    {
        return $this->hasOne(CustomerAddressProxy::modelClass(), 'customer_id')->where('default_address', 1);
    }

    /**
     * Customer's relation with wishlist items
     */
    public function wishlist_items() {
        return $this->hasMany(WishlistProxy::modelClass(), 'customer_id');
    }

    /**
     * get all cart inactive cart instance of a customer
     */
    public function all_carts() {
        return $this->hasMany(CartProxy::modelClass(), 'customer_id');
    }

    /**
     * get inactive cart inactive cart instance of a customer
     */
    public function inactive_carts() {
        return $this->hasMany(CartProxy::modelClass(), 'customer_id')->where('is_active', 0);
    }

    /**
     * get active cart inactive cart instance of a customer
     */
    public function active_carts() {
        return $this->hasMany(CartProxy::modelClass(), 'customer_id')->where('is_active', 1);
    }

    /**
     * get all reviews of a customer
    */
    public function all_reviews() {
        return $this->hasMany(ProductReviewProxy::modelClass(), 'customer_id');
    }

    /**
     * get all orders of a customer
     */
    public function all_orders() {
        return $this->hasMany(OrderProxy::modelClass(), 'customer_id');
    }
}
