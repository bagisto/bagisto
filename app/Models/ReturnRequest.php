<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'return_number',
        'reason',
        'description',
        'images',
        'status', // pending, approved, rejected, completed
        'refund_amount',
        'refund_method', // original_payment, store_credit, bank_transfer
        'admin_note',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
        'completed_at',
    ];

    protected $casts = [
        'images' => 'array',
        'refund_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Methods
    public static function generateReturnNumber(): string
    {
        $prefix = 'RET';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        
        return "{$prefix}{$date}{$random}";
    }

    public function approve(int $approvedBy, ?string $note = null): void
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedBy,
            'admin_note' => $note,
        ]);
    }

    public function reject(int $rejectedBy, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => $rejectedBy,
            'rejection_reason' => $reason,
        ]);
    }

    public function complete(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Process refund
        if ($this->refund_method === 'store_credit') {
            StoreCredit::create([
                'user_id' => $this->user_id,
                'amount' => $this->refund_amount,
                'balance' => $this->refund_amount,
                'type' => 'refund',
                'reference_type' => 'return_request',
                'reference_id' => $this->id,
                'description' => "คืนเงินจากคำขอคืนสินค้า #{$this->return_number}",
                'expires_at' => now()->addYear(),
                'status' => 'active',
            ]);
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($return) {
            if (empty($return->return_number)) {
                $return->return_number = static::generateReturnNumber();
            }
        });
    }
}
