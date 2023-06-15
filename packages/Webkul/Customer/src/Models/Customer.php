<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Webkul\Checkout\Models\CartProxy;
use Webkul\Core\Models\SubscribersListProxy;
use Webkul\Customer\Contracts\Customer as CustomerContract;
use Webkul\Customer\Database\Factories\CustomerFactory;
use Webkul\Customer\Notifications\CustomerResetPassword;
use Webkul\Product\Models\ProductReviewProxy;
use Webkul\Sales\Models\OrderProxy;

class Customer extends Authenticatable implements CustomerContract
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'email',
        'phone',
        'password',
        'api_token',
        'token',
        'customer_group_id',
        'subscribed_to_news_letter',
        'status',
        'is_verified',
        'is_suspended',
        'notes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'api_token',
        'remember_token',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Webkul\Customer\Database\Factories\CustomerFactory
     */
    protected static function newFactory()
    {
        return CustomerFactory::new();
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomerResetPassword($token));
    }

    /**
     * Get image url for the customer profile.
     *
     * @return string|null
     */
    public function getImageUrlAttribute()
    {
        return $this->image_url();
    }

    /**
     * Get the customer full name.
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    /**
     * Get image url for the customer image.
     *
     * @return string|null
     */
    public function image_url()
    {
        if (! $this->image) {
            return;
        }

        return Storage::url($this->image);
    }

    /**
     * Is email exists or not.
     *
     * @param  string  $email
     * @return bool
     */
    public function emailExists($email): bool
    {
        $results = $this->where('email', $email);

        if ($results->count() === 0) {
            return false;
        }

        return true;
    }

    /**
     * Get the customer group that owns the customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(CustomerGroupProxy::modelClass(), 'customer_group_id');
    }

    /**
     * Get the customer address that owns the customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(CustomerAddressProxy::modelClass(), 'customer_id');
    }

    /**
     * Get default customer address that owns the customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function default_address()
    {
        return $this->hasOne(CustomerAddressProxy::modelClass(), 'customer_id')
            ->where('default_address', 1);
    }

    /**
     * Customer's relation with wishlist items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wishlist_items()
    {
        return $this->hasMany(WishlistProxy::modelClass(), 'customer_id');
    }

    /**
     * Is wishlist shared by the customer.
     *
     * @return bool
     */
    public function isWishlistShared(): bool
    {
        return (bool) $this->wishlist_items()->where('shared', 1)->first();
    }

    /**
     * Get wishlist shared link.
     *
     * @return string|null
     */
    public function getWishlistSharedLink()
    {
        return $this->isWishlistShared()
            ? URL::signedRoute('shop.customer.wishlist.shared', ['id' => $this->id])
            : null;
    }

    /**
     * Get all cart inactive cart instance of a customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function all_carts()
    {
        return $this->hasMany(CartProxy::modelClass(), 'customer_id');
    }

    /**
     * Get inactive cart instance of a customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inactive_carts()
    {
        return $this->hasMany(CartProxy::modelClass(), 'customer_id')
            ->where('is_active', 0);
    }

    /**
     * Get active cart instance of a customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function active_carts()
    {
        return $this->hasMany(CartProxy::modelClass(), 'customer_id')
            ->where('is_active', 1);
    }

    /**
     * Get all orders of a customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function all_orders()
    {
        return $this->hasMany(OrderProxy::modelClass(), 'customer_id');
    }

    /**
     * Get all reviews of a customer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function all_reviews()
    {
        return $this->hasMany(ProductReviewProxy::modelClass(), 'customer_id');
    }

    /**
     * Get the customer's subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subscription()
    {
        return $this->hasOne(SubscribersListProxy::modelClass(), 'customer_id');
    }
}
