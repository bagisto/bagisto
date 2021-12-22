<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Product\Type\AbstractType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Sales\Database\Factories\InvoiceItemFactory;
use Webkul\Sales\Contracts\InvoiceItem as InvoiceItemContract;

class InvoiceItem extends Model implements InvoiceItemContract
{
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'additional' => 'array',
    ];

    /**
     * Retrieve type instance
     *
     * @return AbstractType
     */
    public function getTypeInstance(): AbstractType
    {
        return $this->order_item->getTypeInstance();
    }

    /**
     * Get the invoice record associated with the invoice item.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(InvoiceProxy::modelClass());
    }

    /**
     * Get the order item record associated with the invoice item.
     */
    public function order_item(): BelongsTo
    {
        return $this->belongsTo(OrderItemProxy::modelClass());
    }

    /**
     * Get the invoice record associated with the invoice item.
     */
    public function product(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the child item record associated with the invoice item.
     */
    public function child(): HasOne
    {
        return $this->hasOne(InvoiceItemProxy::modelClass(), 'parent_id');
    }

    /**
     * Get the children items.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get order item type
     */
    public function getTypeAttribute()
    {
        return $this->order_item->type;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return InvoiceItemFactory::new();
    }
}