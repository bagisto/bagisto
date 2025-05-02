<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Webkul\Sales\Contracts\Invoice as InvoiceContract;
use Webkul\Sales\Database\Factories\InvoiceFactory;
use Webkul\Sales\Traits\InvoiceReminder;
use Webkul\Sales\Traits\PaymentTerm;

class Invoice extends Model implements InvoiceContract
{
    use HasFactory, InvoiceReminder, PaymentTerm;

    /**
     * Pending invoice.
     */
    public const STATUS_PENDING = 'pending';

    /**
     * Payment pending invoice.
     */
    public const STATUS_PENDING_PAYMENT = 'pending_payment';

    /**
     * Paid invoice.
     */
    public const STATUS_PAID = 'paid';

    /**
     * Overdue invoice.
     */
    public const STATUS_OVERDUE = 'overdue';

    /**
     * Refunded invoice.
     */
    public const STATUS_REFUNDED = 'refunded';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Invoice status.
     *
     * @var array
     */
    protected $statusLabel = [
        self::STATUS_PENDING         => 'Pending',
        self::STATUS_PENDING_PAYMENT => 'Pending Payment',
        self::STATUS_PAID            => 'Paid',
        self::STATUS_OVERDUE         => 'Overdue',
        self::STATUS_REFUNDED        => 'Refunded',
    ];

    /**
     * Invoice status label class.
     *
     * @var array
     */
    protected $statusLabelClass = [
        self::STATUS_PENDING         => 'label-pending',
        self::STATUS_PENDING_PAYMENT => 'label-pending',
        self::STATUS_PAID            => 'label-active',
        self::STATUS_OVERDUE         => 'label-canceled',
        self::STATUS_REFUNDED        => 'label-canceled',
    ];

    /**
     * Returns the status label from status code.
     */
    public function getStatusLabelAttribute()
    {
        return $this->statusLabel[$this->state] ?? $this->state;
    }

    /**
     * Returns the status label class from status code.
     */
    public function getStatusLabelClassAttribute()
    {
        return $this->statusLabelClass[$this->state] ?? 'label-active';
    }

    /**
     * Get the order that belongs to the invoice.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the invoice items record associated with the invoice.
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItemProxy::modelClass())
            ->whereNull('parent_id');
    }

    /**
     * Get the customer record associated with the invoice.
     */
    public function customer(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the channel record associated with the invoice.
     */
    public function channel(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the address for the invoice.
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(OrderAddressProxy::modelClass(), 'order_address_id')
            ->where('address_type', OrderAddress::ADDRESS_TYPE_BILLING);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return InvoiceFactory::new();
    }
}
