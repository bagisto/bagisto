<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\BookingProduct\Contracts\Booking as BookingContract;
use Webkul\Sales\Models\OrderItemProxy;
use Webkul\Sales\Models\OrderProxy;

class Booking extends Model implements BookingContract
{
    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'qty',
        'from',
        'to',
        'order_item_id',
        'booking_product_event_ticket_id',
        'product_id',
        'order_id',
    ];

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
