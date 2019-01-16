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
}