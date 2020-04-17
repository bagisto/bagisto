<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Inventory\Models\InventorySource;
use Webkul\Sales\Contracts\Shipment as ShipmentContract;

class Shipment extends Model implements ShipmentContract
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

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
    public function items()
    {
        return $this->hasMany(ShipmentItemProxy::modelClass());
    }

    /**
     * Get the inventory source associated with the shipment.
     */
    public function inventory_source()
    {
        return $this->belongsTo(InventorySource::class, 'inventory_source_id');
    }

    /**
     * Get the customer record associated with the shipment.
     */
    public function customer()
    {
        return $this->morphTo();
    }

    /**
     * Get the address for the shipment.
     */
    public function address()
    {
        return $this->belongsTo(OrderAddressProxy::modelClass(), 'order_address_id')
            ->where('address_type', OrderAddress::ADDRESS_TYPE_SHIPPING);
    }
}