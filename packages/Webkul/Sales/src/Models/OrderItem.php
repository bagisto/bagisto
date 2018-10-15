<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\OrderItem as OrderItemContract;

class OrderItem extends Model implements OrderItemContract
{
    protected $guarded = ['id', 'child', 'created_at', 'updated_at'];

    /**
     * Get remaining qty for shipping.
     */
    public function getQtyToShipAttribute() {
        return $this->qty_ordered - $this->qty_shipped - $this->qty_refunded - $this->qty_canceled;
    }

    /**
     * Get remaining qty for invoice.
     */
    public function getQtyToInvoiceAttribute() {
        return $this->qty_ordered - $this->qty_invoiced - $this->qty_canceled;
    }

    /**
     * Get remaining qty for cancel.
     */
    public function getQtyToCancelAttribute() {
        return $this->qty_ordered - $this->qty_canceled - $this->qty_invoiced;
    }

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
        return $this->hasOne(OrderItemProxy::modelClass(), 'parent_id');
    }

    /**
     * Get the inventories record associated with the order item.
     */
    public function inventories() {
        return $this->hasMany(CartItemInventoryProxy::modelClass());
    }

    /**
     * Get the invoice items record associated with the order item.
     */
    public function invoice_items() {
        return $this->hasMany(InvoiceItemProxy::modelClass());
    }

    /**
     * Get the shipment items record associated with the order item.
     */
    public function shipment_items() {
        return $this->hasMany(ShipmentItemProxy::modelClass());
    }
}