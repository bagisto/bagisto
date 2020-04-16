<?php


namespace Webkul\Core\Models;


use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Models\Customer;

abstract class Address extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'customer_id',
        'cart_id',
        'order_id',
        'first_name',
        'last_name',
        'gender',
        'company_name',
        'address1',
        'address2',
        'postcode',
        'city',
        'state',
        'country',
        'email',
        'phone',
        'default_address',
    ];

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the customer record associated with the address.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
