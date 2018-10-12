<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Inventory\Models\InventorySource;

class ProductInventory extends Model
{
    public $timestamps = false;

    protected $fillable = ['qty', 'product_id', 'inventory_source_id'];

    /**
     * Use by cart for
     * checking the
     * inventory source
     * status
     *
     * @return Collection
     */
    // public function checkInventoryStatus() {
    //     return $this->leftjoin('inventory_sources', 'inventory_sources.id', 'inventory_source_id')->select('status')->where('status', '=','1');
    // }

    /**
     * Get the product attribute family that owns the product.
     */
    public function inventory_source()
    {
        return $this->belongsTo(InventorySource::class);
    }
}