<?php


namespace Webkul\Core\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Customer\Models\Customer;
use Webkul\Core\Contracts\Address as AddressContract;

/**
 * Class Address
 * @package Webkul\Core\Models
 *
 * @property string       $address_type
 * @property integer      $customer_id
 * @property Customer     $customer
 * @property string       $first_name
 * @property string       $last_name
 * @property string       $gender
 * @property string       $company_name
 * @property string       $address1
 * @property string       $address2
 * @property string       $postcode
 * @property string       $city
 * @property string       $state
 * @property string       $country
 * @property string       $email
 * @property string       $phone
 * @property boolean      $default_address
 * @property array        $additional
 *
 * @property-read integer $id
 * @property-read string  $name
 * @property-read Carbon  $created_at
 * @property-read Carbon  $updated_at
 *
 */
abstract class Address extends Model implements AddressContract
{
    protected $table = 'addresses';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'address_type',
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
        'vat_id',
        'additional',
    ];

    protected $casts = [
        'additional' => 'array',
    ];

    /**
     * Get all of the attributes for the attribute groups.
     */
    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the customer record associated with the address.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
