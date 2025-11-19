<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'balance',
        'type', // refund, compensation, promotion, manual
        'reference_type', // order, return_request, etc.
        'reference_id',
        'description',
        'expires_at',
        'status', // active, used, expired, cancelled
        'used_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance' => 'decimal:2',
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('balance', '>', 0)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now())
            ->where('status', 'active');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function use(float $amount): void
    {
        if ($amount > $this->balance) {
            throw new \Exception('ยอดเครดิตไม่เพียงพอ');
        }

        $this->balance -= $amount;

        if ($this->balance <= 0) {
            $this->status = 'used';
            $this->used_at = now();
        }

        $this->save();
    }

    public function cancel(string $reason): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);
    }

    public function isActive(): bool
    {
        if ($this->status !== 'active' || $this->balance <= 0) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($credit) {
            if (is_null($credit->balance)) {
                $credit->balance = $credit->amount;
            }
        });
    }
}
