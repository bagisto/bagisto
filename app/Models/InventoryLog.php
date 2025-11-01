<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variant_id',
        'type', // sale, return, restock, adjustment, damaged, lost
        'quantity',
        'stock_before',
        'stock_after',
        'reference_type', // order, return_request, purchase_order
        'reference_id',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'stock_before' => 'integer',
        'stock_after' => 'integer',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Methods
    public function getTypeNameAttribute(): string
    {
        $types = [
            'sale' => 'ขาย',
            'return' => 'คืนสินค้า',
            'restock' => 'เติมสต็อก',
            'adjustment' => 'ปรับปรุง',
            'damaged' => 'สินค้าเสียหาย',
            'lost' => 'สินค้าสูญหาย',
        ];

        return $types[$this->type] ?? $this->type;
    }

    public static function logChange(
        int $productId,
        string $type,
        int $quantity,
        int $stockBefore,
        int $stockAfter,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $notes = null
    ): self {
        return static::create([
            'product_id' => $productId,
            'type' => $type,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'user_id' => auth()->id(),
            'notes' => $notes,
        ]);
    }
}
