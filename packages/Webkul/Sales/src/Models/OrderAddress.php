<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Core\Models\Address;
use Webkul\Sales\Contracts\OrderAddress as OrderAddressContract;
use Webkul\Sales\Database\Factories\OrderAddressFactory;

/**
 * Class OrderAddress
 *
 *
 * @property int $order_id
 * @property Order $order
 */
class OrderAddress extends Address implements OrderAddressContract
{
    use HasFactory;

    /**
     * Define the shipping address.
     */
    public const ADDRESS_TYPE_SHIPPING = 'order_shipping';

    /**
     * Define the billing address
     */
    public const ADDRESS_TYPE_BILLING = 'order_billing';

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
        static::addGlobalScope('address_type', function (Builder $builder) {
            $builder->whereIn('address_type', [
                self::ADDRESS_TYPE_BILLING,
                self::ADDRESS_TYPE_SHIPPING,
            ]);
        });

        static::creating(static function ($address) {
            switch ($address->address_type) {
                case CartAddress::ADDRESS_TYPE_BILLING:
                    $address->address_type = self::ADDRESS_TYPE_BILLING;

                    break;

                case CartAddress::ADDRESS_TYPE_SHIPPING:
                    $address->address_type = self::ADDRESS_TYPE_SHIPPING;

                    break;
            }
        });

        parent::boot();
    }

    /**
     * Get the order record associated with the address.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return OrderAddressFactory::new();
    }
}
