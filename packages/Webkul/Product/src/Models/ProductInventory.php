<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;

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
}