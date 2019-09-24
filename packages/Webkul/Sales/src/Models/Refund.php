<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\Refund as RefundContract;

class Refund extends Model implements RefundContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $statusLabel = [
    ];

    /**
     * Returns the status label from status code
     */
    public function getStatusLabelAttribute()
    {
        return isset($this->statusLabel[$this->state]) ? $this->statusLabel[$this->state] : '';
    }

    /**
     * Get the order that belongs to the Refund.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the Refund items record associated with the Refund.
     */
    public function items() {
        return $this->hasMany(RefundItemProxy::modelClass())->whereNull('parent_id');
    }

    /**
     * Get the customer record associated with the Refund.
     */
    public function customer()
    {
        return $this->morphTo();
    }

    /**
     * Get the channel record associated with the Refund.
     */
    public function channel()
    {
        return $this->morphTo();
    }

    /**
     * Get the addresses for the shipment.
     */
    public function address()
    {
        return $this->belongsTo(OrderAddressProxy::modelClass(), 'order_address_id');
    }
}