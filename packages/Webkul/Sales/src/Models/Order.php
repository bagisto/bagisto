<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\Order as OrderContract;

class Order extends Model implements OrderContract
{
    protected $guarded = ['id', 'items', 'shipping_address', 'billing_address', 'customer', 'channel', 'payment', 'created_at', 'updated_at'];

    protected $statusLabel = [
        'pending' => 'Pending',
        'processing' => 'Processing',
        'completed' => 'Completed',
        'canceled' => 'Canceled',
        'closed' => 'Closed',
    ];

    /**
     * Get the order items record associated with the order.
     */
    public function getCustomerFullNameAttribute() {
        return $this->customer_first_name . ' ' . $this->customer_last_name;
    }

    /**
     * Returns the status label from status code
     */
    public function getStatusLabelAttribute()
    {
        return $this->statusLabel[$this->status];
    }

    /**
     * Return base total due amount
     */
    public function getBaseTotalDueAttribute()
    {
        return $this->base_grand_total - $this->base_grand_total_invoiced;
    }

    /**
     * Return total due amount
     */
    public function getTotalDueAttribute()
    {
        return $this->grand_total - $this->grand_total_invoiced;
    }

    /**
     * Get the order items record associated with the order.
     */
    public function items() {
        return $this->hasMany(OrderItemProxy::modelClass())->whereNull('parent_id');
    }

    /**
     * Get the order shipments record associated with the order.
     */
    public function shipments() {
        return $this->hasMany(ShipmentProxy::modelClass());
    }

    /**
     * Get the order invoices record associated with the order.
     */
    public function invoices() {
        return $this->hasMany(InvoiceProxy::modelClass());
    }
    
    /**
     * Get the customer record associated with the order.
     */
    public function customer()
    {
        return $this->morphTo();
    }

    /**
     * Get the addresses for the order.
     */
    public function addresses()
    {
        return $this->hasMany(OrderAddressProxy::modelClass());
    }

    /**
     * Get the payment for the order.
     */
    public function payment()
    {
        return $this->hasOne(OrderPaymentProxy::modelClass());
    }

    /**
     * Get the biling address for the order.
     */
    public function billing_address()
    {
        return $this->addresses()->where('address_type', 'billing');
    }

    /**
     * Get billing address for the order.
     */
    public function getBillingAddressAttribute()
    {
        return $this->billing_address()->first();
    }

    /**
     * Get the shipping address for the order.
     */
    public function shipping_address()
    {
        return $this->addresses()->where('address_type', 'shipping');
    }

    /**
     * Get shipping address for the order.
     */
    public function getShippingAddressAttribute()
    {
        return $this->shipping_address()->first();
    }

    /**
     * Get the channel record associated with the order.
     */
    public function channel()
    {
        return $this->morphTo();
    }

    /**
     * Checks if new shipment is allow or not
     */
    public function canShip()
    {
        foreach ($this->items as $item) {
            if ($item->qty_to_ship > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if new invoice is allow or not
     */
    public function canInvoice()
    {
        foreach ($this->items as $item) {
            if ($item->qty_to_invoice > 0) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Checks if order could can canceled on not
     */
    public function canCancel()
    {
        foreach($this->items as $item) {
            if ($item->qty_to_cancel > 0) {
                return true;
            }
        }

        return false;
    }
}