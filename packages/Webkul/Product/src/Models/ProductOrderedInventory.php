<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Inventory\Models\InventorySourceProxy;
use Webkul\Core\Models\ChannelProxy;
use Webkul\Product\Contracts\ProductOrderedInventory as ProductOrderedInventoryContract;

class ProductOrderedInventory extends Model implements ProductOrderedInventoryContract
{
    public $timestamps = false;

    protected $fillable = ['qty', 'product_id', 'channel_id'];

    /**
     * Get the channel owns the inventory.
     */
    public function channel()
    {
        return $this->belongsTo(ChannelProxy::modelClass());
    }

    /**
     * Get the product that owns the product inventory.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}