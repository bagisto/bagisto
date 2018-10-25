<?php
namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerGroup extends Model
{
    protected $table = 'customer_groups';

    protected $fillable = ['name', 'is_user_defined'];
}
