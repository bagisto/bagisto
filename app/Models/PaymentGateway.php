<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'code', // promptpay, bank_transfer, credit_card, paypal, stripe, omise
        'description',
        'logo',
        'type', // online, offline, wallet
        'is_active',
        'sort_order',
        'settings', // JSON for API keys, credentials, etc.
        'fee_type', // none, fixed, percentage
        'fee_value',
        'min_amount',
        'max_amount',
        'supported_currencies',
        'test_mode',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'settings' => 'array',
        'fee_value' => 'decimal:2',
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'supported_currencies' => 'array',
        'test_mode' => 'boolean',
    ];

    protected $hidden = [
        'settings', // Hide sensitive API keys
    ];

    // Relationships
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function scopeOnline($query)
    {
        return $query->where('type', 'online');
    }

    public function scopeOffline($query)
    {
        return $query->where('type', 'offline');
    }

    // Accessors
    public function getLogoUrlAttribute()
    {
        return $this->logo 
            ? asset('storage/' . $this->logo) 
            : asset('images/payment-default.png');
    }

    // Methods
    public function calculateFee(float $amount): float
    {
        if ($this->fee_type === 'fixed') {
            return $this->fee_value;
        }

        if ($this->fee_type === 'percentage') {
            return $amount * ($this->fee_value / 100);
        }

        return 0;
    }

    public function isAvailableForAmount(float $amount): bool
    {
        if ($this->min_amount && $amount < $this->min_amount) {
            return false;
        }

        if ($this->max_amount && $amount > $this->max_amount) {
            return false;
        }

        return true;
    }

    public function supportsCurrency(string $currency): bool
    {
        if (!$this->supported_currencies) {
            return true; // If not specified, support all
        }

        return in_array($currency, $this->supported_currencies);
    }

    public function getSetting(string $key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    public function updateSetting(string $key, $value): void
    {
        $settings = $this->settings ?? [];
        $settings[$key] = $value;
        $this->update(['settings' => $settings]);
    }
}
