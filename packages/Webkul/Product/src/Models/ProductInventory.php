<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Inventory\Models\InventorySourceProxy;
use Webkul\Product\Contracts\ProductInventory as ProductInventoryContract;

class ProductInventory extends Model implements ProductInventoryContract
{
    public $timestamps = false;

    protected $fillable = [
        'qty',
        'product_id',
        'inventory_source_id',
        'vendor_id',
    ];

    /**
     * Get the product attribute family that owns the product.
     */
    public function inventory_source()
    {
        return $this->belongsTo(InventorySourceProxy::modelClass());
    }

    /**
     * Get the product that owns the product inventory.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}