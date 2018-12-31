<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\Shipment as ShipmentContract;

class Shipment extends Model implements ShipmentContract
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Get the order that belongs to the invoice.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the shipment items record associated with the shipment.
     */
    public function items() {
        return $this->hasMany(ShipmentItemProxy::modelClass());
    }

    /**
     * Get the customer record associated with the shipment.
     */
    public function customer()
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