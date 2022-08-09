<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Inventory\Models\InventorySource;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Sales\Database\Factories\ShipmentFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Sales\Contracts\Shipment as ShipmentContract;

class Shipment extends Model implements ShipmentContract
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the order that belongs to the invoice.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the shipment items record associated with the shipment.
     */
    public function items(): HasMany
    {
        return $this->hasMany(ShipmentItemProxy::modelClass());
    }

    /**
     * Get the inventory source associated with the shipment.
     */
    public function inventory_source(): BelongsTo
    {
        return $this->belongsTo(InventorySource::class, 'inventory_source_id');
    }

    /**
     * Get the customer record associated with the shipment.
     */
    public function customer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the address for the shipment.
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(OrderAddressProxy::modelClass(), 'order_address_id')
            ->where('address_type', OrderAddress::ADDRESS_TYPE_SHIPPING);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ShipmentFactory::new();
    }
}