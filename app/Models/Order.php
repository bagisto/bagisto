<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'status', // pending, confirmed, processing, shipped, delivered, cancelled, refunded
        'payment_status', // pending, paid, failed, refunded
        'payment_method',
        'payment_gateway_id',
        'shipping_method_id',
        'coupon_id',
        'coupon_code',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'shipping_amount',
        'total_amount',
        'currency',
        'customer_note',
        'admin_note',
        'shipping_address',
        'billing_address',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
        'ip_address',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    public function returnRequests()
    {
        return $this->hasMany(ReturnRequest::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function shippingMethod()
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function paymentGateway()
    {
        return $this->belongsTo(PaymentGateway::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'pending');
    }

    // Methods
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        
        return "{$prefix}{$date}{$random}";
    }

    public function updateStatus(string $status, ?string $note = null): void
    {
        $oldStatus = $this->status;
        
        $this->update(['status' => $status]);

        // Create status history
        $this->statusHistory()->create([
            'status' => $status,
            'note' => $note,
            'created_by' => auth()->id(),
        ]);

        // Update timestamps based on status
        if ($status === 'shipped' && !$this->shipped_at) {
            $this->update(['shipped_at' => now()]);
        }

        if ($status === 'delivered' && !$this->delivered_at) {
            $this->update(['delivered_at' => now()]);
        }

        if ($status === 'cancelled' && !$this->cancelled_at) {
            $this->update(['cancelled_at' => now()]);
            
            // Restore stock
            foreach ($this->items as $item) {
                $item->product->incrementStock($item->quantity);
            }
        }
    }

    public function calculateTotals(): void
    {
        $subtotal = $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $discountAmount = $this->discount_amount ?? 0;
        $taxAmount = ($subtotal - $discountAmount) * 0.07; // 7% VAT
        $shippingAmount = $this->shipping_amount ?? 0;

        $total = $subtotal - $discountAmount + $taxAmount + $shippingAmount;

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $total,
        ]);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function canBeRefunded(): bool
    {
        return $this->payment_status === 'paid' 
            && in_array($this->status, ['delivered', 'cancelled']);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber();
            }

            if (empty($order->currency)) {
                $order->currency = 'THB';
            }

            $order->ip_address = request()->ip();
        });

        static::created(function ($order) {
            // Create initial status history
            $order->statusHistory()->create([
                'status' => $order->status,
                'note' => 'คำสั่งซื้อถูกสร้าง',
                'created_by' => $order->user_id,
            ]);
        });
    }
}
