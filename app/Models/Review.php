<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'title',
        'comment',
        'images',
        'is_verified_purchase',
        'is_approved',
        'approved_at',
        'approved_by',
        'helpful_count',
        'not_helpful_count',
        'vendor_reply',
        'vendor_replied_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'images' => 'array',
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'vendor_replied_at' => 'datetime',
        'helpful_count' => 'integer',
        'not_helpful_count' => 'integer',
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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function scopeVerifiedPurchase($query)
    {
        return $query->where('is_verified_purchase', true);
    }

    public function scopeByRating($query, int $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Methods
    public function approve(int $approvedBy): void
    {
        $this->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => $approvedBy,
        ]);

        // Update product rating
        $this->product->updateRating();
    }

    public function addVendorReply(string $reply): void
    {
        $this->update([
            'vendor_reply' => $reply,
            'vendor_replied_at' => now(),
        ]);
    }

    public function markAsHelpful(): void
    {
        $this->increment('helpful_count');
    }

    public function markAsNotHelpful(): void
    {
        $this->increment('not_helpful_count');
    }

    public function getHelpfulPercentageAttribute(): float
    {
        $total = $this->helpful_count + $this->not_helpful_count;
        
        if ($total === 0) {
            return 0;
        }

        return round(($this->helpful_count / $total) * 100, 2);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($review) {
            // Update product rating
            $review->product->updateRating();
        });

        static::deleted(function ($review) {
            // Update product rating
            $review->product->updateRating();
        });
    }
}
