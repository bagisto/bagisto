<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'variant_id',
        'quantity',
        'price',
        'special_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'special_price' => 'decimal:2',
    ];

    // Relationships
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    // Accessors
    public function getFinalPriceAttribute()
    {
        return $this->special_price ?? $this->price;
    }

    public function getSubtotalAttribute()
    {
        return $this->final_price * $this->quantity;
    }

    // Methods
    public function updatePrice(): void
    {
        if ($this->variant) {
            $this->update([
                'price' => $this->variant->price,
                'special_price' => $this->variant->special_price,
            ]);
        } else {
            $this->update([
                'price' => $this->product->price,
                'special_price' => $this->product->special_price,
            ]);
        }
    }

    public function isAvailable(): bool
    {
        if ($this->variant) {
            return $this->variant->isInStock() && $this->variant->stock_quantity >= $this->quantity;
        }

        return $this->product->isInStock() && $this->product->stock_quantity >= $this->quantity;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            // Set price from product or variant
            if ($item->variant) {
                $item->price = $item->variant->price;
                $item->special_price = $item->variant->special_price;
            } else {
                $item->price = $item->product->price;
                $item->special_price = $item->product->special_price;
            }
        });

        static::saved(function ($item) {
            $item->cart->calculateTotals();
        });

        static::deleted(function ($item) {
            $item->cart->calculateTotals();
        });
    }
}
