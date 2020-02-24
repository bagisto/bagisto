<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\BookingProduct\Contracts\Booking as BookingContract;
use Webkul\Sales\Models\OrderProxy;
use Webkul\Sales\Models\OrderItemProxy;

class Booking extends Model implements BookingContract
{
    public $timestamps = false;

    protected $fillable = ['from', 'to', 'order_item_id', 'order_id'];

    /**
     * Get the order record associated with the order item.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the child item record associated with the order item.
     */
    public function order_item()
    {
        return $this->hasOne(OrderItemProxy::modelClass());
    }
}