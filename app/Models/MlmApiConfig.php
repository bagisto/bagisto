<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MlmApiConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_url',
        'api_key',
        'api_secret',
        'webhook_url',
        'webhook_secret',
        'sync_enabled',
        'sync_interval', // in minutes
        'last_sync_at',
        'settings', // JSON for additional settings
        'is_active',
    ];

    protected $casts = [
        'sync_enabled' => 'boolean',
        'sync_interval' => 'integer',
        'last_sync_at' => 'datetime',
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'api_key',
        'api_secret',
        'webhook_secret',
    ];

    // Relationships
    public function syncLogs()
    {
        return $this->hasMany(MlmSyncLog::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSyncEnabled($query)
    {
        return $query->where('sync_enabled', true);
    }

    // Methods
    public function updateLastSync(): void
    {
        $this->update(['last_sync_at' => now()]);
    }

    public function shouldSync(): bool
    {
        if (!$this->sync_enabled || !$this->is_active) {
            return false;
        }

        if (!$this->last_sync_at) {
            return true;
        }

        return $this->last_sync_at->addMinutes($this->sync_interval)->isPast();
    }

    public function testConnection(): array
    {
        try {
            // Implement API connection test
            $response = \Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->api_key,
            ])->get($this->api_url . '/health');

            return [
                'success' => $response->successful(),
                'message' => $response->successful() ? 'เชื่อมต่อสำเร็จ' : 'เชื่อมต่อล้มเหลว',
                'status_code' => $response->status(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'status_code' => 0,
            ];
        }
    }

    public function logSync(string $type, string $status, ?string $message = null, ?array $data = null): MlmSyncLog
    {
        return $this->syncLogs()->create([
            'sync_type' => $type,
            'status' => $status,
            'message' => $message,
            'request_data' => $data['request'] ?? null,
            'response_data' => $data['response'] ?? null,
            'error_message' => $data['error'] ?? null,
        ]);
    }
}
