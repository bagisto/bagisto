<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Sales\Database\Factories\OrderItemFactory;
use Webkul\Product\Type\AbstractType;
use Webkul\Sales\Contracts\OrderItem as OrderItemContract;

class OrderItem extends Model implements OrderItemContract
{
    use HasFactory;

    protected $guarded = [
        'id',
        'child',
        'children',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'additional' => 'array',
    ];

    protected $typeInstance;

    /**
     * Retrieve type instance
     *
     * @return AbstractType
     */
    public function getTypeInstance(): AbstractType
    {
        if ($this->typeInstance) {
            return $this->typeInstance;
        }

        $this->typeInstance = app(config('product_types.' . $this->type . '.class'));

        if ($this->product) {
            $this->typeInstance->setProduct($this->product);
        }

        return $this->typeInstance;
    }

    /**
     * @return bool
     */
    public function isStockable(): bool
    {
        return $this->getTypeInstance()->isStockable();
    }

    /**
     * Checks if new shipment is allowed or not
     */
    public function canShip(): bool
    {
        if (! $this->isStockable()) {
            return false;
        }

        if ($this->qty_to_ship > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get remaining qty for shipping.
     */
    public function getQtyToShipAttribute()
    {
        if (! $this->isStockable()) {
            return 0;
        }

        return $this->qty_ordered - $this->qty_shipped - $this->qty_refunded - $this->qty_canceled;
    }

    /**
     * Checks if new invoice is allow or not
     */
    public function canInvoice()
    {
        if ($this->qty_to_invoice > 0) {
            return true;
        }

        return false;
    }

    /**
     * Get remaining qty for invoice.
     */
    public function getQtyToInvoiceAttribute()
    {
        return $this->qty_ordered - $this->qty_invoiced - $this->qty_canceled;
    }

    /**
     * Checks if new cancel is allow or not
     */
    public function canCancel(): bool
    {
        return $this->qty_to_cancel > 0;
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
    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the product record associated with the order item.
     */
    public function product(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the child item record associated with the order item.
     */
    public function child(): HasOne
    {
        return $this->hasOne(OrderItemProxy::modelClass(), 'parent_id');
    }

    /**
     * Get the parent item record associated with the order item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the children items.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get the invoice items record associated with the order item.
     */
    public function invoice_items(): HasMany
    {
        return $this->hasMany(InvoiceItemProxy::modelClass());
    }

    /**
     * Get the shipment items record associated with the order item.
     */
    public function shipment_items(): HasMany
    {
        return $this->hasMany(ShipmentItemProxy::modelClass());
    }

    /**
     * Get the refund items record associated with the order item.
     */
    public function refund_items(): HasMany
    {
        return $this->hasMany(RefundItemProxy::modelClass());
    }

    /**
     * Returns configurable option html
     */
    public function downloadable_link_purchased(): HasMany
    {
        return $this->hasMany(DownloadableLinkPurchasedProxy::modelClass());
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = parent::toArray();

        if (! $this->id) {
            return $array;
        }

        $array['qty_to_ship'] = $this->qty_to_ship;

        $array['qty_to_invoice'] = $this->qty_to_invoice;

        $array['qty_to_cancel'] = $this->qty_to_cancel;

        $array['qty_to_refund'] = $this->qty_to_refund;

        $array['downloadable_links'] = $this->downloadable_link_purchased;

        return $array;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return OrderItemFactory::new();
    }
}