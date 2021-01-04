<?php
namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Contracts\CustomerGroup as CustomerGroupContract;

class CustomerGroup extends Model implements CustomerGroupContract
{
    protected $table = 'customer_groups';

    protected $fillable = ['name', 'code', 'is_user_defined'];

    /**
     * Get the customers for this group.
    */
    public function customers()
    {
        return $this->hasMany(CustomerProxy::modelClass());
    }
}
