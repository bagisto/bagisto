<?php

namespace Webkul\Customer\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Webkul\Checkout\Models\CartProxy;
use Webkul\Sales\Models\OrderProxy;
use Webkul\Core\Models\SubscribersListProxy;
use Webkul\Product\Models\ProductReviewProxy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Customer\Database\Factories\CustomerFactory;
use Webkul\Customer\Notifications\CustomerResetPassword;
use Webkul\Customer\Contracts\Customer as CustomerContract;
use Webkul\Customer\Database\Factories\CustomerAddressFactory;

class Customer extends Authenticatable implements CustomerContract, JWTSubject
{
    use Notifiable, HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'email',
        'phone',
        'password',
        'api_token',
        'customer_group_id',
        'subscribed_to_news_letter',
        'is_verified',
        'token',
        'notes',
        'status',
    ];

    protected $hidden = [
        'password',
        'api_token',
        'remember_token',
    ];

    /**
     * Get the customer full name.
     */
    public function getNameAttribute(): string
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->last_name);
    }

    /**
     * Email exists or not
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
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(CustomerGroupProxy::modelClass(), 'customer_group_id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     *
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomerResetPassword($token));
    }

    /**
     * Get the customer address that owns the customer.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddressProxy::modelClass(), 'customer_id');
    }

    /**
     * Get default customer address that owns the customer.
     */
    public function default_address(): HasOne
    {
        return $this->hasOne(CustomerAddressProxy::modelClass(), 'customer_id')
                    ->where('default_address', 1);
    }

    /**
     * Customer's relation with wishlist items
     */
    public function wishlist_items(): HasMany
    {
        return $this->hasMany(WishlistProxy::modelClass(), 'customer_id');
    }

    /**
     * get all cart inactive cart instance of a customer
     */
    public function all_carts(): HasMany
    {
        return $this->hasMany(CartProxy::modelClass(), 'customer_id');
    }

    /**
     * get inactive cart instance of a customer
     */
    public function inactive_carts(): HasMany
    {
        return $this->hasMany(CartProxy::modelClass(), 'customer_id')
                    ->where('is_active', 0);
    }

    /**
     * get active cart inactive cart instance of a customer
     */
    public function active_carts(): HasMany
    {
        return $this->hasMany(CartProxy::modelClass(), 'customer_id')
                    ->where('is_active', 1);
    }

    /**
     * get all reviews of a customer
     */
    public function all_reviews(): HasMany
    {
        return $this->hasMany(ProductReviewProxy::modelClass(), 'customer_id');
    }

    /**
     * get all orders of a customer
     */
    public function all_orders(): HasMany
    {
        return $this->hasMany(OrderProxy::modelClass(), 'customer_id');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Get the customer's subscription.
     */
    public function subscription(): HasOne
    {
        return $this->hasOne(SubscribersListProxy::modelClass(), 'customer_id');
    }

    /**
     * Create a new factory instance for the model
     *
     * @return CustomerFactory
     */
    protected static function newFactory(): CustomerFactory
    {
        return CustomerFactory::new();
    }
}
