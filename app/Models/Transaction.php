<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'transaction_number',
        'payment_gateway_id',
        'payment_method',
        'amount',
        'currency',
        'status', // pending, processing, completed, failed, cancelled, refunded
        'gateway_transaction_id',
        'gateway_response',
        'payment_details',
        'ip_address',
        'user_agent',
        'processed_at',
        'failed_at',
        'failure_reason',
        'refunded_at',
        'refund_amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'gateway_response' => 'array',
        'payment_details' => 'array',
        'processed_at' => 'datetime',
        'failed_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function paymentGateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    // Methods
    public static function generateTransactionNumber(): string
    {
        $prefix = 'TXN';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 8));
        
        return "{$prefix}{$date}{$random}";
    }

    public function markAsCompleted(string $gatewayTransactionId, ?array $response = null): void
    {
        $this->update([
            'status' => 'completed',
            'gateway_transaction_id' => $gatewayTransactionId,
            'gateway_response' => $response,
            'processed_at' => now(),
        ]);

        // Update order payment status
        if ($this->order) {
            $this->order->update(['payment_status' => 'paid']);
        }
    }

    public function markAsFailed(string $reason, ?array $response = null): void
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
            'gateway_response' => $response,
            'failed_at' => now(),
        ]);

        // Update order payment status
        if ($this->order) {
            $this->order->update(['payment_status' => 'failed']);
        }
    }

    public function refund(float $amount, string $reason): Refund
    {
        $refund = Refund::create([
            'transaction_id' => $this->id,
            'order_id' => $this->order_id,
            'user_id' => $this->user_id,
            'amount' => $amount,
            'reason' => $reason,
            'status' => 'pending',
        ]);

        return $refund;
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_number)) {
                $transaction->transaction_number = static::generateTransactionNumber();
            }

            if (empty($transaction->currency)) {
                $transaction->currency = 'THB';
            }

            $transaction->ip_address = request()->ip();
            $transaction->user_agent = request()->userAgent();
        });
    }
}
