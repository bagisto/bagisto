<?php

namespace Webkul\Sales\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Contracts\OrderTransaction as OrderTransactionContract;
use Webkul\Sales\Database\Factories\OrderTransactionFactory;

/**
 * OrderTransaction class
 * @category Models
 * @author Webkul
 * @property int $id
 * @property string $transaction_id
 * @property string $status
 * @property string $type
 * @property float $amount
 * @property string $payment_method
 * @property ?int $order_id
 * @property ?int $invoice_id
 * @property array $data
 * @property-read string $payment_title
 */
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

    protected $fillable = [
        'transaction_id',
        'status',
        'type',
        'amount',
        'payment_method',
        'order_id',
        'invoice_id',
        'data',
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
