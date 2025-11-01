<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'price',
        'special_price',
        'stock_quantity',
        'weight',
        'image',
        'attributes', // JSON: {"size": "L", "color": "Red"}
        'is_default',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'special_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'weight' => 'decimal:2',
        'attributes' => 'array',
        'is_default' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'variant_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Accessors
    public function getFinalPriceAttribute()
    {
        return $this->special_price ?? $this->price;
    }

    public function getImageUrlAttribute()
    {
        return $this->image 
            ? asset('storage/' . $this->image) 
            : $this->product->main_image;
    }

    public function getAttributeTextAttribute()
    {
        if (!$this->attributes) {
            return '';
        }

        return collect($this->attributes)
            ->map(fn($value, $key) => ucfirst($key) . ': ' . $value)
            ->implode(', ');
    }

    // Methods
    public function isInStock(): bool
    {
        return $this->stock_quantity > 0;
    }

    public function decrementStock(int $quantity): void
    {
        if ($this->stock_quantity < $quantity) {
            throw new \Exception('สินค้าไม่เพียงพอ');
        }

        $this->decrement('stock_quantity', $quantity);
    }

    public function incrementStock(int $quantity): void
    {
        $this->increment('stock_quantity', $quantity);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($variant) {
            if (empty($variant->sku)) {
                $variant->sku = $variant->product->sku . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
            }
        });
    }
}
