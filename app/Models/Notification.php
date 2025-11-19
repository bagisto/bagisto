<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type', // order, product, promotion, system, message
        'title',
        'message',
        'action_url',
        'icon',
        'is_read',
        'read_at',
        'data', // JSON for additional data
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'data' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query, int $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    // Methods
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function markAsUnread(): void
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    public static function send(int $userId, string $type, string $title, string $message, ?string $actionUrl = null, ?array $data = null): self
    {
        return static::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
            'data' => $data,
        ]);
    }

    public static function sendToMultiple(array $userIds, string $type, string $title, string $message, ?string $actionUrl = null, ?array $data = null): void
    {
        foreach ($userIds as $userId) {
            static::send($userId, $type, $title, $message, $actionUrl, $data);
        }
    }

    public static function markAllAsRead(int $userId): void
    {
        static::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    public static function deleteOld(int $days = 30): int
    {
        return static::where('created_at', '<', now()->subDays($days))
            ->where('is_read', true)
            ->delete();
    }

    public function getIconClassAttribute(): string
    {
        $icons = [
            'order' => 'shopping-cart',
            'product' => 'package',
            'promotion' => 'tag',
            'system' => 'bell',
            'message' => 'message-circle',
        ];

        return $icons[$this->type] ?? 'bell';
    }
}
