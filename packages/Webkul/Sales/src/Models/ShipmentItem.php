<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\ShipmentItem as ShipmentItemContract;

class ShipmentItem extends Model implements ShipmentItemContract
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = [
        'id',
        'child',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'additional' => 'array',
    ];

    /**
     * Retrieve type instance.
     *
     * @return AbstractType
     */
    public function getTypeInstance()
    {
        return $this->order_item->getTypeInstance();
    }

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
     * Get order item type.
     */
    public function getTypeAttribute()
    {
        return $this->order_item->type;
    }
}
