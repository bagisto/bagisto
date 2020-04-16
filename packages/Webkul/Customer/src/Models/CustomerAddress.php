<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Builder;
use Webkul\Core\Models\Address;
use Webkul\Customer\Contracts\CustomerAddress as CustomerAddressContract;

class CustomerAddress extends Address implements CustomerAddressContract
{
    public const ADDRESS_TYPE = 'customer';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('address_type', function (Builder $builder) {
            $builder->where('address_type', self::ADDRESS_TYPE);
        });
    }
}
