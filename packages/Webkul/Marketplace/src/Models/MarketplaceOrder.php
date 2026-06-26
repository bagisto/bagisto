<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Marketplace\Contracts\MarketplaceOrder as MarketplaceOrderContract;
use Webkul\Marketplace\Enums\CommissionStatus;
use Webkul\Sales\Models\Order;

class MarketplaceOrder extends Model implements MarketplaceOrderContract
{
    protected $table = 'marketplace_orders';

    protected $fillable = [
        'order_id',
        'seller_id',
        'base_total',
        'commission_amount',
        'seller_total',
        'commission_rate',
        'commission_status',
        'paid_at',
    ];

    protected $casts = [
        'commission_status' => CommissionStatus::class,
        'base_total'        => 'decimal:4',
        'commission_amount' => 'decimal:4',
        'seller_total'      => 'decimal:4',
        'commission_rate'   => 'decimal:2',
        'paid_at'           => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function markAsPaid(): void
    {
        $this->update([
            'commission_status' => CommissionStatus::Paid,
            'paid_at'           => now(),
        ]);
    }
}
