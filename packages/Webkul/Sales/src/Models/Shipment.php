<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\Shipment as ShipmentContract;

class Shipment extends Model implements ShipmentContract
{
    /**
     * Get the shipment items record associated with the shipment.
     */
    public function items() {
        return $this->hasMany(ShipmentItemProxy::modelClass())->whereNull('parent_id');
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
    public function addresses()
    {
        return $this->hasMany(OrderAddressProxy::modelClass());
    }

    /**
     * Get the biling address for the shipment.
     */
    public function billing_address()
    {
        return $this->addresses()->where('address_type', 'billing');
    }

    /**
     * Get billing address for the shipment.
     */
    public function getBillingAddressAttribute()
    {
        return $this->billing_address()->first();
    }

    /**
     * Get the shipping address for the shipment.
     */
    public function shipping_address()
    {
        return $this->addresses()->where('address_type', 'shipping');
    }

    /**
     * Get shipping address for the shipment.
     */
    public function getShippingAddressAttribute()
    {
        return $this->shipping_address()->first();
    }
}