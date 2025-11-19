<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MlmSyncLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'mlm_api_config_id',
        'sync_type', // commission, user, order, payout
        'status', // success, failed, pending
        'message',
        'request_data',
        'response_data',
        'error_message',
        'duration', // in milliseconds
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
        'duration' => 'integer',
    ];

    // Relationships
    public function apiConfig()
    {
        return $this->belongsTo(MlmApiConfig::class, 'mlm_api_config_id');
    }

    // Scopes
    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('sync_type', $type);
    }

    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Methods
    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public static function getSuccessRate(int $days = 7): float
    {
        $total = static::recent($days)->count();
        
        if ($total === 0) {
            return 0;
        }

        $success = static::recent($days)->success()->count();
        
        return round(($success / $total) * 100, 2);
    }

    public static function getAverageDuration(string $type = null): float
    {
        $query = static::success();

        if ($type) {
            $query->byType($type);
        }

        return round($query->avg('duration') ?? 0, 2);
    }
}
