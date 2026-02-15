<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Marketplace\Contracts\SellerTransaction as SellerTransactionContract;

class SellerTransaction extends Model implements SellerTransactionContract
{
    protected $table = 'marketplace_seller_transactions';

    protected $fillable = [
        'seller_id',
        'transaction_id',
        'type',
        'amount',
        'base_amount',
        'comment',
        'method',
    ];

    protected $casts = [
        'amount'      => 'decimal:4',
        'base_amount' => 'decimal:4',
    ];

    /**
     * Get the seller.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(SellerProxy::modelClass());
    }
}
