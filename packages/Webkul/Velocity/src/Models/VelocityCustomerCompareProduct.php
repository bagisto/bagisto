<?php

namespace Webkul\Velocity\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Velocity\Contracts\VelocityCustomerCompareProduct as VelocityCustomerCompareProductContract;

class VelocityCustomerCompareProduct extends Model implements VelocityCustomerCompareProductContract
{
    protected $guarded = [];
}