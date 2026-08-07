<?php

namespace Webkul\PayGlocal\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\PayGlocal\Contracts\PayGlocalTransaction as PayGlocalTransactionContract;
use Webkul\PayGlocal\Enums\PayGlocalTransactionStatus;

class PayGlocalTransaction extends Model implements PayGlocalTransactionContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payglocal_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cart_id',
        'order_id',
        'merchant_txn_id',
        'gid',
        'status_url',
        'amount',
        'currency',
        'status',
    ];

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
        'status' => PayGlocalTransactionStatus::class,
    ];
}
