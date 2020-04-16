<?php

namespace Webkul\Sales\Models;

use Webkul\Core\Models\Address;
use Webkul\Sales\Contracts\OrderAddress as OrderAddressContract;
use Illuminate\Database\Eloquent\Builder;

class OrderAddress extends Address implements OrderAddressContract
{
    public const ADDRESS_TYPE_SHIPPING = 'order_address_shipping';
    public const ADDRESS_TYPE_BILLING = 'order_address_billing';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('address_type', function (Builder $builder) {
            $builder->whereIn('address_type', [
                self::ADDRESS_TYPE_BILLING,
                self::ADDRESS_TYPE_SHIPPING
            ]);
        });
    }

    /**
     * Get the order record associated with the address.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}