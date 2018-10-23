<?php
namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    protected $table = 'customer_groups';

    protected $fillable = ['group_name', 'is_user_defined'];
}
