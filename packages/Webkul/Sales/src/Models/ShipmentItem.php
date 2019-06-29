<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\ShipmentItem as ShipmentItemContract;

class ShipmentItem extends Model implements ShipmentItemContract
{
    protected $guarded = ['id', 'child', 'created_at', 'updated_at'];

    protected $casts = [
        'additional' => 'array',
    ];
    
    /**
     * Get the shipment record associated with the shipment item.
     */
    public function shipment()
    {
        return $this->belongsTo(ShipmentProxy::modelClass());
    }

    /**
     * Get the order item record associated with the shipment item.
     */
    public function order_item()
    {
        return $this->belongsTo(OrderItemProxy::modelClass());
    }

    /**
     * Get the shipment record associated with the shipment item.
     */
    public function product()
    {
        return $this->morphTo();
    }

    /**
     * Get the child item record associated with the shipment item.
     */
    public function child()
    {
        return $this->belongsTo(ShipmentItemProxy::modelClass(), 'parent_id');
    }

    /**
     * Get order item type
     */
    public function getTypeAttribute()
    {
        return $this->order_item->type;
    }

    /**
     * Returns configurable option html
     */
    public function getOptionDetailHtml()
    {
        return $this->order_item->getOptionDetailHtml();
    }

    /**
     * Returns configurable option html
     */
    public function getDownloadableDetailHtml()
    {
        return $this->order_item->getDownloadableDetailHtml();
    }
}