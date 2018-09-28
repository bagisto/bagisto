<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\OrderAddress as OrderAddressContract;
use Webkul\Customer\Models\Customer;

class OrderAddress extends Model implements OrderAddressContract
{
    protected $table = 'order_address';

    /**
     * Get of the customer fullname.
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    /**
     * Get the customer record associated with the order.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}