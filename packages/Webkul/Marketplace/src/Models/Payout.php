<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Marketplace\Contracts\Payout as PayoutContract;
use Webkul\Marketplace\Enums\PayoutStatus;

class Payout extends Model implements PayoutContract
{
    protected $table = 'marketplace_payouts';

    protected $fillable = [
        'seller_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'payment_details',
        'transaction_id',
        'notes',
        'paid_at',
    ];

    protected $casts = [
        'status'          => PayoutStatus::class,
        'payment_details' => 'array',
        'amount'          => 'decimal:4',
        'paid_at'         => 'datetime',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function markAsPaid(string $transactionId): void
    {
        $this->update([
            'status'         => PayoutStatus::Paid,
            'transaction_id' => $transactionId,
            'paid_at'        => now(),
        ]);
    }
}
