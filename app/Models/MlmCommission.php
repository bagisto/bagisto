<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MlmCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'downline_user_id',
        'mlm_package_id',
        'order_id',
        'level',
        'commission_amount',
        'base_amount',
        'commission_rate',
        'status', // pending, approved, paid, cancelled
        'paid_at',
        'external_id', // ID from external MLM system
        'synced_at',
        'sync_status',
        'sync_error',
        'notes',
    ];

    protected $casts = [
        'level' => 'integer',
        'commission_amount' => 'decimal:2',
        'base_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'paid_at' => 'datetime',
        'synced_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function downlineUser()
    {
        return $this->belongsTo(User::class, 'downline_user_id');
    }

    public function mlmPackage()
    {
        return $this->belongsTo(MlmPackage::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
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

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeUnsynced($query)
    {
        return $query->whereNull('synced_at');
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByLevel($query, int $level)
    {
        return $query->where('level', $level);
    }

    // Methods
    public function approve(): void
    {
        $this->update(['status' => 'approved']);
    }

    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);
    }

    public function cancel(string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => $reason,
        ]);
    }

    public function markAsSynced(string $externalId): void
    {
        $this->update([
            'external_id' => $externalId,
            'synced_at' => now(),
            'sync_status' => 'success',
            'sync_error' => null,
        ]);
    }

    public function markSyncFailed(string $error): void
    {
        $this->update([
            'sync_status' => 'failed',
            'sync_error' => $error,
        ]);
    }

    public static function getTotalCommissionByUser(int $userId, ?string $status = null): float
    {
        $query = static::where('user_id', $userId);

        if ($status) {
            $query->where('status', $status);
        }

        return $query->sum('commission_amount');
    }

    public static function getCommissionsByLevel(int $userId): array
    {
        return static::where('user_id', $userId)
            ->selectRaw('level, SUM(commission_amount) as total, COUNT(*) as count')
            ->groupBy('level')
            ->orderBy('level')
            ->get()
            ->pluck('total', 'level')
            ->toArray();
    }
}
