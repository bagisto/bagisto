<?php

namespace Webkul\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Inventory\Contracts\InventorySource as InventorySourceContract;

class InventorySource extends Model implements InventorySourceContract
{
    protected $guarded = ['_token'];
}