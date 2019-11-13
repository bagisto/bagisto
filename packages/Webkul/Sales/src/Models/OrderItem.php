<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\OrderItem as OrderItemContract;
use Webkul\Product\Models\Product;

class OrderItem extends Model implements OrderItemContract
{
    protected $guarded = ['id', 'child', 'created_at', 'updated_at'];

    protected $casts = [
        'additional' => 'array',
    ];

    /**
     * Get remaining qty for shipping.
     */
    public function getQtyToShipAttribute()
    {
        return $this->qty_ordered - $this->qty_shipped - $this->qty_refunded - $this->qty_canceled;
    }

    /**
     * Get remaining qty for invoice.
     */
    public function getQtyToInvoiceAttribute()
    {
        return $this->qty_ordered - $this->qty_invoiced - $this->qty_canceled;
    }

    /**
     * Get remaining qty for cancel.
     */
    public function getQtyToCancelAttribute()
    {
        return $this->qty_ordered - $this->qty_canceled - $this->qty_invoiced;
    }

    /**
     * Get remaining qty for refund.
     */
    public function getQtyToRefundAttribute()
    {
        return $this->qty_invoiced - $this->qty_refunded;
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
     * Get the invoice items record associated with the order item.
     */
    public function invoice_items()
    {
        return $this->hasMany(InvoiceItemProxy::modelClass());
    }

    /**
     * Get the shipment items record associated with the order item.
     */
    public function shipment_items()
    {
        return $this->hasMany(ShipmentItemProxy::modelClass());
    }

    /**
     * Get the refund items record associated with the order item.
     */
    public function refund_items()
    {
        return $this->hasMany(RefundItemProxy::modelClass());
    }

    /**
     * Returns configurable option html
     */
    public function getOptionDetailHtml()
    {

        if ($this->type == 'configurable' && isset($this->additional['attributes'])) {
            $labels = [];

            foreach ($this->additional['attributes'] as $attribute) {
                $labels[] = $attribute['attribute_name'] . ' : ' . $attribute['option_label'];
            }

            return implode(', ', $labels);
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        $array['qty_to_ship'] = $this->qty_to_ship;

        $array['qty_to_invoice'] = $this->qty_to_invoice;

        $array['qty_to_cancel'] = $this->qty_to_cancel;

        $array['qty_to_refund'] = $this->qty_to_refund;

        return $array;
    }
}