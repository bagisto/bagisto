<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'payout_number',
        'amount',
        'commission_amount',
        'net_amount',
        'currency',
        'payment_method', // bank_transfer, paypal, stripe
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'status', // pending, processing, completed, failed, cancelled
        'processed_at',
        'processed_by',
        'transaction_reference',
        'notes',
        'failure_reason',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
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

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeForPeriod($query, $start, $end)
    {
        return $query->whereBetween('period_start', [$start, $end]);
    }

    // Methods
    public static function generatePayoutNumber(): string
    {
        $prefix = 'PO';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        
        return "{$prefix}{$date}{$random}";
    }

    public function calculateNetAmount(): void
    {
        $this->net_amount = $this->amount - $this->commission_amount;
        $this->save();
    }

    public function markAsProcessing(int $processedBy): void
    {
        $this->update([
            'status' => 'processing',
            'processed_by' => $processedBy,
        ]);
    }

    public function markAsCompleted(string $transactionReference, int $processedBy): void
    {
        $this->update([
            'status' => 'completed',
            'processed_at' => now(),
            'processed_by' => $processedBy,
            'transaction_reference' => $transactionReference,
        ]);
    }

    public function markAsFailed(string $reason, int $processedBy): void
    {
        $this->update([
            'status' => 'failed',
            'processed_at' => now(),
            'processed_by' => $processedBy,
            'failure_reason' => $reason,
        ]);
    }

    public function cancel(): void
    {
        if ($this->status === 'completed') {
            throw new \Exception('ไม่สามารถยกเลิกการจ่ายเงินที่เสร็จสมบูรณ์แล้ว');
        }

        $this->update(['status' => 'cancelled']);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payout) {
            if (empty($payout->payout_number)) {
                $payout->payout_number = static::generatePayoutNumber();
            }

            if (empty($payout->currency)) {
                $payout->currency = 'THB';
            }

            // Calculate net amount if not set
            if (is_null($payout->net_amount)) {
                $payout->net_amount = $payout->amount - ($payout->commission_amount ?? 0);
            }
        });
    }
}
