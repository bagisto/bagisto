<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'vendor_id',
        'product_name',
        'product_sku',
        'variant_name',
        'quantity',
        'price',
        'special_price',
        'tax_amount',
        'discount_amount',
        'total',
        'mlm_commission_amount',
        'vendor_commission_amount',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'special_price' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'mlm_commission_amount' => 'decimal:2',
        'vendor_commission_amount' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
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
    public function calculateTotal(): void
    {
        $subtotal = $this->final_price * $this->quantity;
        $total = $subtotal + $this->tax_amount - $this->discount_amount;

        $this->update(['total' => $total]);
    }

    public function calculateCommissions(): void
    {
        $mlmCommission = 0;
        $vendorCommission = 0;

        // Calculate MLM commission
        if ($this->product->is_mlm_product && $this->product->mlmPackage) {
            $mlmCommission = $this->product->calculateMlmCommission($this->quantity);
        }

        // Calculate vendor commission
        if ($this->vendor) {
            $vendorCommission = $this->vendor->calculateCommission($this->subtotal);
        }

        $this->update([
            'mlm_commission_amount' => $mlmCommission,
            'vendor_commission_amount' => $vendorCommission,
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            // Store product details
            $item->product_name = $item->product->name;
            $item->product_sku = $item->product->sku;
            $item->vendor_id = $item->product->vendor_id;

            if ($item->variant) {
                $item->variant_name = $item->variant->name;
            }

            // Calculate total
            $subtotal = $item->final_price * $item->quantity;
            $item->total = $subtotal + ($item->tax_amount ?? 0) - ($item->discount_amount ?? 0);
        });

        static::created(function ($item) {
            $item->calculateCommissions();
        });
    }
}
