<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'description',
        'code',
        'type', // flat_rate, free_shipping, weight_based, price_based, carrier
        'cost',
        'min_order_amount', // For free shipping threshold
        'max_weight',
        'estimated_days_min',
        'estimated_days_max',
        'carrier_name', // Kerry, Flash, Thailand Post, etc.
        'tracking_url',
        'is_active',
        'sort_order',
        'settings', // JSON for additional settings
    ];

    protected $casts = [
        'cost' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'estimated_days_min' => 'integer',
        'estimated_days_max' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'settings' => 'array',
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

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Methods
    public function calculateCost(float $orderTotal, float $weight = 0): float
    {
        if ($this->type === 'free_shipping') {
            if ($this->min_order_amount && $orderTotal >= $this->min_order_amount) {
                return 0;
            }
            return $this->cost;
        }

        if ($this->type === 'flat_rate') {
            return $this->cost;
        }

        if ($this->type === 'weight_based') {
            // Implement weight-based calculation
            $rates = $this->settings['weight_rates'] ?? [];
            foreach ($rates as $rate) {
                if ($weight <= $rate['max_weight']) {
                    return $rate['cost'];
                }
            }
            return $this->cost;
        }

        if ($this->type === 'price_based') {
            // Implement price-based calculation
            $rates = $this->settings['price_rates'] ?? [];
            foreach ($rates as $rate) {
                if ($orderTotal <= $rate['max_price']) {
                    return $rate['cost'];
                }
            }
            return $this->cost;
        }

        return $this->cost;
    }

    public function getEstimatedDeliveryAttribute(): string
    {
        if ($this->estimated_days_min === $this->estimated_days_max) {
            return "{$this->estimated_days_min} วัน";
        }

        return "{$this->estimated_days_min}-{$this->estimated_days_max} วัน";
    }

    public function getTrackingUrlForOrder(string $trackingNumber): ?string
    {
        if (!$this->tracking_url) {
            return null;
        }

        return str_replace('{tracking_number}', $trackingNumber, $this->tracking_url);
    }
}
