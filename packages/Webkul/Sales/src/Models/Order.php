<?php

namespace Webkul\Sales\Models;

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

	public const STATUS_PENDING         = 'pending';

	public const STATUS_PENDING_PAYMENT = 'pending_payment';

	public const STATUS_PROCESSING      = 'processing';

	public const STATUS_COMPLETED       = 'completed';

	public const STATUS_CANCELED        = 'canceled';

	public const STATUS_CLOSED          = 'closed';

	public const STATUS_FRAUD           = 'fraud';

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

	protected array $statusLabel = [
		self::STATUS_PENDING         => 'Pending',
		self::STATUS_PENDING_PAYMENT => 'Pending Payment',
		self::STATUS_PROCESSING      => 'Processing',
		self::STATUS_COMPLETED       => 'Completed',
		self::STATUS_CANCELED        => 'Canceled',
		self::STATUS_CLOSED          => 'Closed',
		self::STATUS_FRAUD           => 'Fraud',
	];

	/**
	 * Get the order items record associated with the order.
	 *
	 * @return string
	 */
	public function getCustomerFullNameAttribute(): string
    {
        return $this->customer_first_name . ' ' . $this->customer_last_name;
    }

	/**
	 * Returns the status label from status code
	 *
	 * @return string
	 */
	public function getStatusLabelAttribute(): string
	{
        return $this->statusLabel[$this->status];
    }

	/**
	 * Return base total due amount
	 *
	 * @return float
	 */
	public function getBaseTotalDueAttribute(): float
	{
        return $this->base_grand_total - $this->base_grand_total_invoiced;
    }

	/**
	 * Return total due amount
	 *
	 * @return float
	 */
	public function getTotalDueAttribute(): float
	{
        return $this->grand_total - $this->grand_total_invoiced;
    }

	/**
	 * Get the associated cart that was used to create this order.
	 *
	 * @return BelongsTo
	 */
	public function cart(): BelongsTo
    {
        return $this->belongsTo(CartProxy::modelClass());
    }

	/**
	 * Get the order items record associated with the order.
	 *
	 * @return HasMany
	 */
	public function items(): HasMany
    {
        return $this->hasMany(OrderItemProxy::modelClass())
                    ->whereNull('parent_id');
    }

	/**
	 * Get the comments record associated with the order.
	 *
	 * @return HasMany
	 */
	public function comments(): HasMany
	{
        return $this->hasMany(OrderCommentProxy::modelClass());
    }

	/**
	 * Get the order items record associated with the order.
	 *
	 * @return HasMany
	 */
	public function all_items(): HasMany
    {
        return $this->hasMany(OrderItemProxy::modelClass());
    }

	/**
	 * Get the order shipments record associated with the order.
	 *
	 * @return HasMany
	 */
	public function shipments(): HasMany
    {
        return $this->hasMany(ShipmentProxy::modelClass());
    }

	/**
	 * Get the order invoices record associated with the order.
	 *
	 * @return HasMany
	 */
	public function invoices(): HasMany
    {
        return $this->hasMany(InvoiceProxy::modelClass());
    }

	/**
	 * Get the order refunds record associated with the order.
	 *
	 * @return HasMany
	 */
	public function refunds(): HasMany
    {
        return $this->hasMany(RefundProxy::modelClass());
    }

	/**
	 * Get the order transactions record associated with the order.
	 *
	 * @return HasMany
	 */
	public function transactions(): HasMany
    {
        return $this->hasMany(OrderTransactionProxy::modelClass());
    }

	/**
	 * Get the customer record associated with the order.
	 *
	 * @return MorphTo
	 */
	public function customer(): MorphTo
    {
        return $this->morphTo();
    }

	/**
	 * Get the addresses for the order.
	 *
	 * @return HasMany
	 */
	public function addresses(): HasMany
    {
        return $this->hasMany(OrderAddressProxy::modelClass());
    }

	/**
	 * Get the payment for the order.
	 *
	 * @return HasOne
	 */
	public function payment(): HasOne
	{
        return $this->hasOne(OrderPaymentProxy::modelClass());
    }

	/**
	 * Get the billing address for the order.
	 *
	 * @return HasMany
	 */
	public function billing_address(): HasMany
    {
        return $this->addresses()->where('address_type', OrderAddress::ADDRESS_TYPE_BILLING);
    }

	/**
	 * Get billing address for the order.
	 *
	 * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\HasMany|object|null
	 */
	public function getBillingAddressAttribute()
    {
        return $this->billing_address()->first();
    }

	/**
	 * Get the shipping address for the order.
	 *
	 * @return HasMany
	 */
	public function shipping_address(): HasMany
    {
        return $this->addresses()->where('address_type', OrderAddress::ADDRESS_TYPE_SHIPPING);
    }

    /**
     * Get shipping address for the order.
     */
    public function getShippingAddressAttribute()
    {
        return $this->shipping_address()->first();
    }

	/**
	 * Get the channel record associated with the order.
	 *
	 * @return MorphTo
	 */
	public function channel(): MorphTo
	{
        return $this->morphTo();
    }

    /**
     * Checks if cart have stockable items
     *
     * @return boolean
     */
    public function haveStockableItems(): bool
    {
        foreach ($this->items as $item) {
            if ($item->getTypeInstance()
                     ->isStockable()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if new shipment is allow or not
     *
     * @return bool
     */
    public function canShip(): bool
    {
        if ($this->status === self::STATUS_FRAUD) {
            return false;
        }

        foreach ($this->items as $item) {
            if ($item->order->status !== self::STATUS_CLOSED && $item->canShip()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if new invoice is allow or not
     *
     * @return bool
     */
    public function canInvoice(): bool
    {
        if ($this->status === self::STATUS_FRAUD) {
            return false;
        }

        foreach ($this->items as $item) {
            if ($item->order->status !== self::STATUS_CLOSED && $item->canInvoice()) {
                return true;
            }
        }

        return false;
    }
    
    /**
     * Verify if a invoice is still unpaid
     *
     * @return bool
     */
    public function hasOpenInvoice(): bool
    {
        $pendingInvoice = $this->invoices()->where('state', 'pending')
        ->orWhere('state', 'pending_payment')
        ->first();

        if ($pendingInvoice) {
            return true;
        }

        return false;
    }

    /**
     * Checks if order can be canceled or not
     *
     * @return bool
     */
    public function canCancel(): bool
    {
        if ($this->payment->method === 'cashondelivery' && core()->getConfigData('sales.paymentmethods.cashondelivery.generate_invoice')) {
            return false;
        }

        if ($this->payment->method === 'moneytransfer' && core()->getConfigData('sales.paymentmethods.moneytransfer.generate_invoice')) {
            return false;
        }

        if ($this->status === self::STATUS_FRAUD) {
            return false;
        }

        $pendingInvoice = $this->invoices->where('state', 'pending')->first();

        if ($pendingInvoice) {
            return true;
        }

        foreach ($this->items as $item) {
            if ($item->order->status !== self::STATUS_CLOSED && $item->canCancel()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if order can be refunded or not
     *
     * @return bool
     */
    public function canRefund(): bool
    {
        if ($this->status === self::STATUS_FRAUD) {
            return false;
        }

        $pendingInvoice = $this->invoices->where('state', 'pending')->first();

        if ($pendingInvoice) {
            return false;
        }

        foreach ($this->items as $item) {
            if ($item->qty_to_refund > 0 && $item->order->status !== self::STATUS_CLOSED) {
                return true;
            }
        }

        if ($this->base_grand_total_invoiced - $this->base_grand_total_refunded - $this->refunds()->sum('base_adjustment_fee') > 0) {
            return true;
        }

        return false;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return OrderFactory
     */
    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }
}
