<?php

namespace Webkul\Discount\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Inventory\Contracts\InventorySource as InventorySourceContract;

class CatalogRuleChannels extends Model implements InventorySourceContract
{
}