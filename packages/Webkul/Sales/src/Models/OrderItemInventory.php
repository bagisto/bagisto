<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\OrderItemInventory as OrderItemInventoryContract;

class OrderItemInventory extends Model implements OrderItemInventoryContract
{
    protected $guarded = ['id', 'child', 'created_at', 'updated_at'];

    /**
     * Get the order item record associated with the order item inventory.
     */
    public function order_item()
    {
        return $this->belongsTo(OrderItemProxy::modelClass());
    }
}