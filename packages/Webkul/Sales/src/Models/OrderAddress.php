<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Contracts\OrderAddress as OrderAddressContract;

class OrderAddress extends Model implements OrderAddressContract
{
    protected $table = 'order_address';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'company_name',
        'vat_id',
        'address1',
        'address2',
        'city',
        'state',
        'postcode',
        'country',
        'phone',
        'address_type',
        'cart_id',
        'customer_id',
    ];

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