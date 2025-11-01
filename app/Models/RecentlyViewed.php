<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecentlyViewed extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'viewed_at',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scopes
    public function scopeRecent($query, int $limit = 10)
    {
        return $query->orderBy('viewed_at', 'desc')->limit($limit);
    }

    // Methods
    public static function track(int $userId, int $productId): void
    {
        static::updateOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $productId,
            ],
            [
                'viewed_at' => now(),
            ]
        );

        // Keep only last 50 items
        $count = static::where('user_id', $userId)->count();
        if ($count > 50) {
            $oldestIds = static::where('user_id', $userId)
                ->orderBy('viewed_at')
                ->limit($count - 50)
                ->pluck('id');

            static::whereIn('id', $oldestIds)->delete();
        }
    }
}
