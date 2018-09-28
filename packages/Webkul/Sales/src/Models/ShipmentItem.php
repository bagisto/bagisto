<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\ShipmentItem as ShipmentItemContract;

class ShipmentItem extends Model implements ShipmentItemContract
{
    /**
     * Get the shipment record associated with the shipment item.
     */
    public function shipment()
    {
        return $this->belongsTo(ShipmentProxy::modelClass());
    }

    /**
     * Get the order item record associated with the shipment item.
     */
    public function order_item()
    {
        return $this->belongsTo(OrderItemProxy::modelClass());
    }

    /**
     * Get the shipment record associated with the shipment item.
     */
    public function product()
    {
        return $this->morphTo();
    }

    /**
     * Get the child item record associated with the shipment item.
     */
    public function child()
    {
        return $this->belongsTo(ShipmentItemProxy::modelClass(), 'parent_id');
    }
}