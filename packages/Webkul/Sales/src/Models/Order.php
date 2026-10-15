<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\Checkout\Models\CartProxy;
use Webkul\Sales\Contracts\Order as OrderContract;
use Webkul\Sales\Database\Factories\OrderFactory;

class Order extends Model implements OrderContract
{
    use HasFactory;

    /**
     * Pending state.
     */
    public const STATUS_PENDING = 'pending';

    /**
     * Payment pending state.
     */
    public const STATUS_PENDING_PAYMENT = 'pending_payment';

    /**
     * Processing state.
     */
    public const STATUS_PROCESSING = 'processing';

    /**
     * Completed state.
     */
    public const STATUS_COMPLETED = 'completed';

    /**
     * Canceled state.
     */
    public const STATUS_CANCELED = 'canceled';

    /**
     * Closed state.
     */
    public const STATUS_CLOSED = 'closed';

    /**
     * Fraud state.
     */
    public const STATUS_FRAUD = 'fraud';

    /**
     * Dates.
     *
     * @var array
     */
    protected $dates = ['created_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['datetime'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'items',
        'shipping_address',
        'billing_address',
        'customer',
        'channel',
        'payment',
        'created_at',
        'updated_at',
    ];

    /**
     * Attribute casting.
     *
     * @var array
     */
    protected $casts = [
        'total_item_count' => 'integer',
        'total_qty_ordered' => 'integer',
        'is_guest' => 'boolean',
    ];

    /**
     * Status label.
     *
     * @var array
     */
    protected $statusLabel = [
        self::STATUS_PENDING => 'Pending',
        self::STATUS_PENDING_PAYMENT => 'Pending Payment',
        self::STATUS_PROCESSING => 'Processing',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELED => 'Canceled',
        self::STATUS_CLOSED => 'Closed',
        self::STATUS_FRAUD => 'Fraud',
    ];

    /**
     * Get the order items record associated with the order.
     */
    public function getCustomerFullNameAttribute(): string
    {
        return $this->customer_first_name.' '.$this->customer_last_name;
    }

    /**
     * Returns the status label from status code.
     */
    public function getStatusLabelAttribute()
    {
        return $this->statusLabel[$this->status];
    }

    /**
     * The amount still to be invoiced, in the base currency.
     */
    public function getBaseTotalDueAttribute(): float
    {
        return $this->dueAfterCancellations(
            $this->base_grand_total - $this->base_grand_total_invoiced,
            'base_price'
        );
    }

    /**
     * The amount still to be invoiced, in the currency the order was placed in.
     */
    public function getTotalDueAttribute(): float
    {
        return $this->dueAfterCancellations(
            $this->grand_total - $this->grand_total_invoiced,
            'price'
        );
    }

    /**
     * Return human friendly date.
     */
    public function getDatetimeAttribute()
    {
        return $this->created_at?->diffForHumans();
    }

    /**
     * Get the associated cart that was used to create this order.
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(CartProxy::modelClass());
    }

    /**
     * Get the order items record associated with the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItemProxy::modelClass())
            ->whereNull('parent_id');
    }

    /**
     * Get the comments record associated with the order.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(OrderCommentProxy::modelClass());
    }

    /**
     * Get the order items record associated with the order.
     */
    public function all_items(): HasMany
    {
        return $this->hasMany(OrderItemProxy::modelClass());
    }

    /**
     * Get the order record associated with the item.
     */
    public function downloadable_link_purchased()
    {
        return $this->hasMany(DownloadableLinkPurchasedProxy::modelClass());
    }

    /**
     * Get the order shipments record associated with the order.
     */
    public function shipments(): HasMany
    {
        return $this->hasMany(ShipmentProxy::modelClass());
    }

    /**
     * Get the order invoices record associated with the order.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(InvoiceProxy::modelClass());
    }

    /**
     * Get the order refunds record associated with the order.
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(RefundProxy::modelClass());
    }

    /**
     * Get the order transactions record associated with the order.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(OrderTransactionProxy::modelClass());
    }

    /**
     * Get the customer record associated with the order.
     */
    public function customer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the addresses for the order.
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(OrderAddressProxy::modelClass());
    }

    /**
     * Get the payment for the order.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(OrderPaymentProxy::modelClass());
    }

    /**
     * Get the billing address for the order.
     */
    public function billing_address()
    {
        return $this->addresses
            ->where('address_type', OrderAddress::ADDRESS_TYPE_BILLING);
    }

    /**
     * Get billing address for the order.
     */
    public function getBillingAddressAttribute()
    {
        return $this->billing_address()
            ->first();
    }

    /**
     * Get the shipping address for the order.
     */
    public function shipping_address()
    {
        return $this->addresses
            ->where('address_type', OrderAddress::ADDRESS_TYPE_SHIPPING);
    }

    /**
     * Get shipping address for the order.
     */
    public function getShippingAddressAttribute()
    {
        return $this->shipping_address()
            ->first();
    }

    /**
     * Get the channel record associated with the order.
     */
    public function channel()
    {
        return $this->morphTo();
    }

    /**
     * Checks if cart have stockable items.
     */
    public function haveStockableItems(): bool
    {
        foreach ($this->items as $item) {
            if ($item->getTypeInstance()->isStockable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verify if a invoice is still unpaid.
     */
    public function hasOpenInvoice(): bool
    {
        $pendingInvoice = $this->invoices()
            ->where('state', Invoice::STATUS_PENDING)
            ->orWhere('state', Invoice::STATUS_PENDING_PAYMENT)
            ->first();

        return $pendingInvoice
            ? true
            : false;
    }

    /**
     * Checks if new shipment is allow or not.
     */
    public function canShip(): bool
    {
        foreach ($this->items as $item) {
            if (
                $item->canShip()
                && ! in_array($item->order->status, [
                    self::STATUS_CLOSED,
                    self::STATUS_FRAUD,
                ])
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if new invoice is allow or not.
     */
    public function canInvoice(): bool
    {
        foreach ($this->items as $item) {
            if (
                $item->canInvoice()
                && ! in_array($item->order->status, [
                    self::STATUS_CLOSED,
                    self::STATUS_FRAUD,
                ])
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if order can be canceled or not.
     *
     * @param  bool  $force  When true, customer-facing cancellation policies
     *                       (currently the booking `allow_cancellation` flag)
     *                       are ignored. Intended for admin overrides.
     */
    public function canCancel(bool $force = false): bool
    {
        foreach ($this->items as $item) {
            if (
                $item->canCancel($force)
                && ! in_array($item->order->status, [
                    self::STATUS_CLOSED,
                    self::STATUS_FRAUD,
                ])
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if order can be refunded or not.
     */
    public function canRefund(): bool
    {
        foreach ($this->items as $item) {
            if (
                $item->qty_to_refund > 0
                && ! in_array($item->order->status, [
                    self::STATUS_CLOSED,
                    self::STATUS_FRAUD,
                ])
            ) {
                return true;
            }
        }

        if ($this->base_grand_total_invoiced - $this->base_grand_total_refunded - $this->refunds()->sum('base_adjustment_fee') > 0) {
            return true;
        }

        return false;
    }

    /**
     * Checks whether every one of the given order item ids is an item of this order.
     */
    public function ownsItems(array $itemIds): bool
    {
        return ! array_diff(
            array_map('intval', $itemIds),
            $this->items->pluck('id')->map('intval')->all()
        );
    }

    /**
     * Checks if order can be reorder or not.
     */
    public function canReorder(): bool
    {
        if ($this->is_guest) {
            return false;
        }

        $hasReorderable = false;

        foreach ($this->items as $item) {
            if ($item->type === 'booking') {
                continue;
            }

            if (! $item->product?->getTypeInstance()->isSaleable()) {
                return false;
            }

            $hasReorderable = true;
        }

        return $hasReorderable;
    }

    /**
     * Take the cancelled quantities off a due total. Cancelling an item leaves the order's grand
     * total alone, so what is owed has to discount what will now never be invoiced.
     */
    protected function dueAfterCancellations(float $due, string $priceColumn): float
    {
        if ($this->status === self::STATUS_CANCELED) {
            return 0;
        }

        foreach ($this->items as $item) {
            $due -= $item->{$priceColumn} * $item->qty_canceled;
        }

        return $due;
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return OrderFactory::new();
    }
}
