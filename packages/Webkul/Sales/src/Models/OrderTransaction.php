<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\OrderTransaction as OrderTransactionContract;
use Webkul\Sales\Database\Factories\OrderTransactionFactory;

class OrderTransaction extends Model implements OrderTransactionContract
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_transactions';

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
     * Get payment method title
     */
    public function getPaymentTitleAttribute()
    {
        if (! $this->payment_method) {
            return;
        }

        return config('payment_methods')[$this->payment_method]['title'];
    }

    /**
     * Create a new factory instance for the model
     */
    protected static function newFactory(): Factory
    {
        return OrderTransactionFactory::new();
    }
}
