<?php

namespace Webkul\EUWithdrawal\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Core\Models\ChannelProxy;
use Webkul\Customer\Models\CustomerProxy;
use Webkul\EUWithdrawal\Contracts\Withdrawal as WithdrawalContract;
use Webkul\Sales\Models\OrderProxy;
use Webkul\User\Models\AdminProxy;

class Withdrawal extends Model implements WithdrawalContract
{
    /**
     * Evidence columns (immutable after insert). Enforced by WithdrawalObserver.
     *
     * @var string[]
     */
    public const EVIDENCE_COLUMNS = [
        'uuid',
        'order_id',
        'customer_id',
        'is_guest',
        'customer_email',
        'channel_id',
        'locale',
        'reason_text',
        'received_at',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eu_withdrawals';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'uuid',
        'order_id',
        'customer_id',
        'is_guest',
        'customer_email',
        'channel_id',
        'locale',
        'reason_text',
        'received_at',
        'confirmation_sent_at',
        'final_confirmation_sent_at',
        'confirmation_error',
        'status',
        'declined_at',
        'declined_reason',
        'declined_by_user_id',
        'refunded_at',
        'refunded_by_user_id',
        'refund_note',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_guest' => 'boolean',
        'received_at' => 'datetime',
        'confirmation_sent_at' => 'datetime',
        'final_confirmation_sent_at' => 'datetime',
        'declined_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    /**
     * The order this withdrawal was filed against.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * The customer who filed the withdrawal (null for guest declarations).
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerProxy::modelClass());
    }

    /**
     * The storefront channel the withdrawal was submitted from.
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(ChannelProxy::modelClass());
    }

    /**
     * Admin user who recorded the decline (null when not yet declined or
     * when the user has since been hard-deleted from the admins table).
     */
    public function declinedBy(): BelongsTo
    {
        return $this->belongsTo(AdminProxy::modelClass(), 'declined_by_user_id');
    }

    /**
     * Admin user who recorded the refund (null when not yet refunded or
     * when the user has since been hard-deleted from the admins table).
     */
    public function refundedBy(): BelongsTo
    {
        return $this->belongsTo(AdminProxy::modelClass(), 'refunded_by_user_id');
    }
}
