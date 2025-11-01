<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_id',
        'alert_type', // low_stock, out_of_stock, overstock
        'threshold',
        'current_stock',
        'status', // pending, acknowledged, resolved
        'acknowledged_at',
        'acknowledged_by',
        'resolved_at',
        'resolved_by',
        'notes',
    ];

    protected $casts = [
        'threshold' => 'integer',
        'current_stock' => 'integer',
        'acknowledged_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function acknowledgedBy()
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAcknowledged($query)
    {
        return $query->where('status', 'acknowledged');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeLowStock($query)
    {
        return $query->where('alert_type', 'low_stock');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('alert_type', 'out_of_stock');
    }

    // Methods
    public function acknowledge(int $userId, ?string $notes = null): void
    {
        $this->update([
            'status' => 'acknowledged',
            'acknowledged_at' => now(),
            'acknowledged_by' => $userId,
            'notes' => $notes,
        ]);
    }

    public function resolve(int $userId, ?string $notes = null): void
    {
        $this->update([
            'status' => 'resolved',
            'resolved_at' => now(),
            'resolved_by' => $userId,
            'notes' => $notes,
        ]);
    }

    public static function checkAndCreate(int $productId, ?int $variantId = null): void
    {
        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            $stock = $variant->stock_quantity;
            $threshold = 10; // Default threshold
        } else {
            $product = Product::find($productId);
            $stock = $product->stock_quantity;
            $threshold = 10; // Default threshold
        }

        $alertType = $stock === 0 ? 'out_of_stock' : 'low_stock';

        if ($stock <= $threshold) {
            static::firstOrCreate(
                [
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'status' => 'pending',
                ],
                [
                    'alert_type' => $alertType,
                    'threshold' => $threshold,
                    'current_stock' => $stock,
                ]
            );
        }
    }

    public function getAlertTypeNameAttribute(): string
    {
        $types = [
            'low_stock' => 'สต็อกต่ำ',
            'out_of_stock' => 'สินค้าหมด',
            'overstock' => 'สต็อกเกิน',
        ];

        return $types[$this->alert_type] ?? $this->alert_type;
    }
}
