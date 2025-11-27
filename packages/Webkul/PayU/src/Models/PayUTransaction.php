<?php

namespace Webkul\PayU\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\PayU\Contracts\PayUTransaction as PayUTransactionContract;
use Webkul\PayU\Enums\TransactionStatus;

class PayUTransaction extends Model implements PayUTransactionContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payu_transactions';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status'   => TransactionStatus::class,
        'response' => 'array',
        'amount'   => 'decimal:4',
    ];
}
