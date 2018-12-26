<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Inventory\Models\InventorySource;
use Webkul\Core\Models\Channel;

class ProductOrderedInventory extends Model
{
    public $timestamps = false;

    protected $fillable = ['qty', 'product_id', 'channel_id'];

    /**
     * Get the channel owns the inventory.
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Get the product that owns the product inventory.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}