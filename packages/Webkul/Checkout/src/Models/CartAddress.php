<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Builder;
use Webkul\Checkout\Contracts\CartAddress as CartAddressContract;
use Webkul\Core\Models\Address;

class CartAddress extends Address implements CartAddressContract
{
    public const ADDRESS_TYPE_SHIPPING = 'cart_address_shipping';
    public const ADDRESS_TYPE_BILLING = 'cart_address_billing';

    /**
     * @var array default values
     */
    protected $attributes = [
        'address_type' => self::ADDRESS_TYPE_BILLING,
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('address_type', static function (Builder $builder) {
            $builder->whereIn('address_type', [
                self::ADDRESS_TYPE_BILLING,
                self::ADDRESS_TYPE_SHIPPING
            ]);
        });
    }

    /**
     * Get the shipping rates for the cart address.
     */
    public function shipping_rates()
    {
        return $this->hasMany(CartShippingRateProxy::modelClass());
    }

    /**
     * Get the cart record associated with the address.
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}