<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Core\Models\Address;
use Webkul\Customer\Contracts\CustomerAddress as CustomerAddressContract;
use Webkul\Customer\Database\Factories\CustomerAddressFactory;

class CustomerAddress extends Address implements CustomerAddressContract
{
    use HasFactory;

    /**
     * Define the customer address type.
     */
    public const ADDRESS_TYPE = 'customer';

    /**
     * Define the fillable property of the model.
     *
     * @var array
     */
    protected $fillable = [
        'address_type',
        'parent_address_id',
        'customer_id',
        'cart_id',
        'order_id',
        'first_name',
        'last_name',
        'gender',
        'company_name',
        'address',
        'city',
        'state',
        'country',
        'postcode',
        'email',
        'phone',
        'vat_id',
        'default_address',
        'use_for_shipping',
        'additional',
    ];

    /**
     * Define the attributes of the customer address model.
     *
     * @var array default values
     */
    protected $attributes = [
        'address_type' => self::ADDRESS_TYPE,
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function boot(): void
    {
        static::addGlobalScope('address_type', static function (Builder $builder) {
            $builder->where('address_type', self::ADDRESS_TYPE);
        });

        parent::boot();
    }

    /**
     * Create a new factory instance for the model
     */
    protected static function newFactory(): Factory
    {
        return CustomerAddressFactory::new();
    }
}
