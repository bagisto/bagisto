<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Checkout\Contracts\CartAddress as CartAddressContract;
use Webkul\Checkout\Database\Factories\CartAddressFactory;
use Webkul\Core\Models\Address;

/**
 * Class CartAddress
 *
 *
 * @property int $cart_id
 * @property Cart $cart
 */
class CartAddress extends Address implements CartAddressContract
{
    use HasFactory;

    /**
     * Define the address type shipping.
     */
    public const ADDRESS_TYPE_SHIPPING = 'cart_shipping';

    /**
     * Define the address type billing.
     */
    public const ADDRESS_TYPE_BILLING = 'cart_billing';

    /**
     * @var array default values
     */
    protected $attributes = [
        'address_type' => self::ADDRESS_TYPE_BILLING,
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function boot(): void
    {
        static::addGlobalScope('address_type', static function (Builder $builder) {
            $builder->whereIn('address_type', [
                self::ADDRESS_TYPE_BILLING,
                self::ADDRESS_TYPE_SHIPPING,
            ]);
        });

        parent::boot();
    }

    /**
     * Get the shipping rates for the cart address.
     */
    public function shipping_rates(): HasMany
    {
        return $this->hasMany(CartShippingRateProxy::modelClass());
    }

    /**
     * Get the cart record associated with the address.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(CartProxy::modelClass());
    }

    /**
     * Create a new factory instance for the model
     */
    protected static function newFactory(): Factory
    {
        return CartAddressFactory::new();
    }
}
