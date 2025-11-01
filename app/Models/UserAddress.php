<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'address_type', // billing, shipping, both
        'first_name',
        'last_name',
        'phone',
        'address_line1',
        'address_line2',
        'sub_district', // ตำบล
        'district', // อำเภอ
        'province', // จังหวัด
        'postal_code',
        'country',
        'is_default',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeBilling($query)
    {
        return $query->whereIn('address_type', ['billing', 'both']);
    }

    public function scopeShipping($query)
    {
        return $query->whereIn('address_type', ['shipping', 'both']);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->sub_district ? "ตำบล{$this->sub_district}" : null,
            $this->district ? "อำเภอ{$this->district}" : null,
            $this->province ? "จังหวัด{$this->province}" : null,
            $this->postal_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    public function getFormattedThaiAddressAttribute()
    {
        return sprintf(
            "%s %s ต.%s อ.%s จ.%s %s",
            $this->address_line1,
            $this->address_line2 ?? '',
            $this->sub_district,
            $this->district,
            $this->province,
            $this->postal_code
        );
    }

    // Methods
    public function setAsDefault(): void
    {
        // Remove default from other addresses
        static::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }

    public function isBillingAddress(): bool
    {
        return in_array($this->address_type, ['billing', 'both']);
    }

    public function isShippingAddress(): bool
    {
        return in_array($this->address_type, ['shipping', 'both']);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($address) {
            // Set as default if it's the first address
            if (!static::where('user_id', $address->user_id)->exists()) {
                $address->is_default = true;
            }
        });
    }
}
