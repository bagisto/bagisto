<?php
namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $table = 'customer_addresses';

    protected $fillable = ['customer_id' ,'address1', 'address2', 'country', 'state', 'city', 'postcode', 'phone', 'default_address'];
}
