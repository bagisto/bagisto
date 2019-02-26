<?php
namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Contracts\CustomerGroup as CustomerGroupContract;

class CustomerGroup extends Model implements CustomerGroupContract
{
    protected $table = 'customer_groups';

    protected $fillable = ['name', 'is_user_defined'];

    /**
     * Get the customer for this group.
    */
    public function customer()
    {
        return $this->hasMany(CustomerProxy::modelClass());
    }
}
