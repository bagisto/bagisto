<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'order_id',
        'user_id',
        'refund_number',
        'amount',
        'currency',
        'reason',
        'status', // pending, processing, completed, failed, cancelled
        'gateway_refund_id',
        'gateway_response',
        'processed_at',
        'processed_by',
        'failed_at',
        'failure_reason',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'processed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    // Relationships
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
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

    // Methods
    public static function generateRefundNumber(): string
    {
        $prefix = 'REF';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        
        return "{$prefix}{$date}{$random}";
    }

    public function markAsCompleted(string $gatewayRefundId, int $processedBy, ?array $response = null): void
    {
        $this->update([
            'status' => 'completed',
            'gateway_refund_id' => $gatewayRefundId,
            'gateway_response' => $response,
            'processed_at' => now(),
            'processed_by' => $processedBy,
        ]);

        // Update transaction status
        $this->transaction->update([
            'status' => 'refunded',
            'refunded_at' => now(),
            'refund_amount' => $this->amount,
        ]);

        // Update order status
        if ($this->order) {
            $this->order->update(['payment_status' => 'refunded']);
        }
    }

    public function markAsFailed(string $reason, int $processedBy): void
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
            'failed_at' => now(),
            'processed_by' => $processedBy,
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($refund) {
            if (empty($refund->refund_number)) {
                $refund->refund_number = static::generateRefundNumber();
            }

            if (empty($refund->currency)) {
                $refund->currency = 'THB';
            }
        });
    }
}
