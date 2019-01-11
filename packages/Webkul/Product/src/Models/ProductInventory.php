<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Inventory\Models\InventorySource;

class ProductInventory extends Model
{
    public $timestamps = false;

    protected $fillable = ['qty', 'product_id', 'inventory_source_id', 'vendor_id'];
    
    /**
     * Get the product attribute family that owns the product.
     */
    public function inventory_source()
    {
        return $this->belongsTo(InventorySource::class);
    }

    /**
     * Get the product that owns the product inventory.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}