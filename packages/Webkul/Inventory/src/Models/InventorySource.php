<?php

namespace Webkul\Inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Inventory\Database\Factories\InventorySourceFactory;
use Webkul\Inventory\Contracts\InventorySource as InventorySourceContract;

class InventorySource extends Model implements InventorySourceContract
{
    use HasFactory;

    protected $guarded = ['_token'];

    /**
     * Create a new factory instance for the model.
     *
     * @return InventorySourceFactory
     */
    protected static function newFactory(): InventorySourceFactory
    {
        return InventorySourceFactory::new();
    }
}