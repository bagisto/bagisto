<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\Sales\Contracts\Refund as RefundContract;
use Webkul\Sales\Database\Factories\RefundFactory;

class Refund extends Model implements RefundContract
{
    use HasFactory;

    /**
     * Define the guarded property to the model.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Define the status label.
     *
     * @var array
     */
    protected $statusLabel = [];

    /**
     * Returns the status label from status code
     */
    public function getStatusLabelAttribute()
    {
        return $this->statusLabel[$this->state] ?? '';
    }

    /**
     * Get the order that belongs to the Refund.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the Refund items record associated with the Refund.
     */
    public function items(): HasMany
    {
        return $this->hasMany(RefundItemProxy::modelClass())
            ->whereNull('parent_id');
    }

    /**
     * Get the customer record associated with the Refund.
     */
    public function customer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the channel record associated with the Refund.
     */
    public function channel(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the addresses for the shipment.
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(OrderAddressProxy::modelClass(), 'order_address_id');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return RefundFactory::new();
    }
}
