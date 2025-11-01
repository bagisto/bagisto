<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'coupon_id',
        'coupon_code',
        'discount_amount',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'total',
    ];

    protected $casts = [
        'discount_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    // Methods
    public function addItem(int $productId, int $quantity = 1, ?int $variantId = null): CartItem
    {
        $item = $this->items()
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if ($item) {
            $item->increment('quantity', $quantity);
            return $item;
        }

        return $this->items()->create([
            'product_id' => $productId,
            'variant_id' => $variantId,
            'quantity' => $quantity,
        ]);
    }

    public function removeItem(int $itemId): void
    {
        $this->items()->where('id', $itemId)->delete();
        $this->calculateTotals();
    }

    public function updateItemQuantity(int $itemId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeItem($itemId);
            return;
        }

        $this->items()->where('id', $itemId)->update(['quantity' => $quantity]);
        $this->calculateTotals();
    }

    public function clear(): void
    {
        $this->items()->delete();
        $this->update([
            'coupon_id' => null,
            'coupon_code' => null,
            'discount_amount' => 0,
            'subtotal' => 0,
            'tax_amount' => 0,
            'total' => 0,
        ]);
    }

    public function applyCoupon(string $code): bool
    {
        $coupon = Coupon::where('code', $code)
            ->active()
            ->valid()
            ->first();

        if (!$coupon) {
            return false;
        }

        if (!$coupon->canBeUsed($this->user_id)) {
            return false;
        }

        $this->update([
            'coupon_id' => $coupon->id,
            'coupon_code' => $coupon->code,
        ]);

        $this->calculateTotals();
        return true;
    }

    public function removeCoupon(): void
    {
        $this->update([
            'coupon_id' => null,
            'coupon_code' => null,
            'discount_amount' => 0,
        ]);

        $this->calculateTotals();
    }

    public function calculateTotals(): void
    {
        $subtotal = $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $discountAmount = 0;
        if ($this->coupon) {
            $discountAmount = $this->coupon->calculateDiscount($subtotal);
        }

        $taxRate = config('shop.tax_rate', 7); // 7% VAT in Thailand
        $taxAmount = ($subtotal - $discountAmount) * ($taxRate / 100);

        $total = $subtotal - $discountAmount + $taxAmount + ($this->shipping_amount ?? 0);

        $this->update([
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'total' => $total,
        ]);
    }

    public function getItemCount(): int
    {
        return $this->items->sum('quantity');
    }

    public function isEmpty(): bool
    {
        return $this->items->isEmpty();
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($cart) {
            $cart->calculateTotals();
        });
    }
}
