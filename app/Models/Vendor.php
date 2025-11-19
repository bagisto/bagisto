<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'shop_name',
        'shop_name_en',
        'shop_slug',
        'shop_description',
        'shop_logo',
        'shop_banner',
        'business_type', // individual, company
        'tax_id',
        'business_registration_number',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'commission_rate', // Percentage
        'status', // pending, approved, rejected, suspended
        'verified_at',
        'rejected_reason',
        'total_sales',
        'total_products',
        'rating',
        'total_reviews',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'commission_rate' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'rating' => 'decimal:2',
        'total_products' => 'integer',
        'total_reviews' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function documents()
    {
        return $this->hasMany(VendorDocument::class);
    }

    public function payouts()
    {
        return $this->hasMany(VendorPayout::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, Product::class);
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
            ->whereNotNull('verified_at');
    }

    // Accessors
    public function getShopLogoUrlAttribute()
    {
        return $this->shop_logo 
            ? asset('storage/' . $this->shop_logo) 
            : asset('images/default-shop-logo.png');
    }

    public function getShopBannerUrlAttribute()
    {
        return $this->shop_banner 
            ? asset('storage/' . $this->shop_banner) 
            : asset('images/default-shop-banner.png');
    }

    public function getShopUrlAttribute()
    {
        return route('shop.show', $this->shop_slug);
    }

    // Methods
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    public function approve(): void
    {
        $this->update([
            'status' => 'approved',
            'verified_at' => now(),
            'rejected_reason' => null,
        ]);
    }

    public function reject(string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'rejected_reason' => $reason,
            'verified_at' => null,
        ]);
    }

    public function suspend(): void
    {
        $this->update(['status' => 'suspended']);
    }

    public function calculateCommission(float $amount): float
    {
        return $amount * ($this->commission_rate / 100);
    }

    public function updateRating(): void
    {
        $reviews = Review::whereHas('product', function ($query) {
            $query->where('vendor_id', $this->id);
        });

        $this->update([
            'rating' => $reviews->avg('rating') ?? 0,
            'total_reviews' => $reviews->count(),
        ]);
    }

    public function updateStats(): void
    {
        $this->update([
            'total_products' => $this->products()->count(),
            'total_sales' => $this->orders()->sum('total_amount'),
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vendor) {
            if (empty($vendor->shop_slug)) {
                $vendor->shop_slug = \Illuminate\Support\Str::slug($vendor->shop_name);
            }
            
            // Set default commission rate if not set
            if (is_null($vendor->commission_rate)) {
                $vendor->commission_rate = config('marketplace.default_commission_rate', 10);
            }
        });
    }
}
