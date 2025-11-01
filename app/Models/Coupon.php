<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type', // percentage, fixed, free_shipping
        'discount_value',
        'min_purchase_amount',
        'max_discount_amount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'start_date',
        'end_date',
        'is_active',
        'applicable_to', // all, specific_products, specific_categories
        'applicable_ids', // JSON array of product/category IDs
        'exclude_sale_items',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_limit_per_user' => 'integer',
        'used_count' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'applicable_ids' => 'array',
        'exclude_sale_items' => 'boolean',
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')
                    ->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                    ->orWhereRaw('used_count < usage_limit');
            });
    }

    // Methods
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function canBeUsed(?int $userId = null): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($userId && $this->usage_limit_per_user) {
            $userUsageCount = Order::where('user_id', $userId)
                ->where('coupon_id', $this->id)
                ->count();

            if ($userUsageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_purchase_amount) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = $subtotal * ($this->discount_value / 100);

            if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
                $discount = $this->max_discount_amount;
            }

            return $discount;
        }

        if ($this->type === 'fixed') {
            return min($this->discount_value, $subtotal);
        }

        return 0;
    }

    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    public function decrementUsage(): void
    {
        if ($this->used_count > 0) {
            $this->decrement('used_count');
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($coupon) {
            $coupon->code = strtoupper($coupon->code);
        });
    }
}
