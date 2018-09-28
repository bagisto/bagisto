<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\OrderItem as OrderItemContract;

class OrderItem extends Model implements OrderItemContract
{
    /**
     * Get the order record associated with the order item.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the order record associated with the order item.
     */
    public function product()
    {
        return $this->morphTo();
    }

    /**
     * Get the child item record associated with the order item.
     */
    public function child()
    {
        return $this->belongsTo(OrderItemProxy::modelClass(), 'parent_id');
    }
}