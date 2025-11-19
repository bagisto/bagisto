<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'category_id',
        'brand_id',
        'mlm_package_id',
        'sku',
        'name',
        'name_en',
        'slug',
        'description',
        'short_description',
        'price',
        'special_price',
        'special_price_from',
        'special_price_to',
        'cost',
        'weight',
        'length',
        'width',
        'height',
        'stock_quantity',
        'min_order_quantity',
        'max_order_quantity',
        'is_featured',
        'is_new',
        'is_mlm_product',
        'mlm_commission_rate',
        'status', // active, inactive, out_of_stock
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'view_count',
        'order_count',
        'rating',
        'review_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'special_price' => 'decimal:2',
        'cost' => 'decimal:2',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'stock_quantity' => 'integer',
        'min_order_quantity' => 'integer',
        'max_order_quantity' => 'integer',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_mlm_product' => 'boolean',
        'mlm_commission_rate' => 'decimal:2',
        'published_at' => 'datetime',
        'special_price_from' => 'datetime',
        'special_price_to' => 'datetime',
        'view_count' => 'integer',
        'order_count' => 'integer',
        'rating' => 'decimal:2',
        'review_count' => 'integer',
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function mlmPackage()
    {
        return $this->belongsTo(MlmPackage::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes')
            ->withPivot('attribute_value_id')
            ->withTimestamps();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock_quantity', '>', 0);
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('special_price')
            ->where(function ($q) {
                $q->whereNull('special_price_from')
                    ->orWhere('special_price_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('special_price_to')
                    ->orWhere('special_price_to', '>=', now());
            });
    }

    public function scopeMlmProducts($query)
    {
        return $query->where('is_mlm_product', true);
    }

    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('name_en', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%")
                ->orWhere('sku', 'like', "%{$term}%");
        });
    }

    // Accessors
    public function getFinalPriceAttribute()
    {
        if ($this->isOnSale()) {
            return $this->special_price;
        }
        return $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->isOnSale() && $this->price > 0) {
            return round((($this->price - $this->special_price) / $this->price) * 100, 2);
        }
        return 0;
    }

    public function getMainImageAttribute()
    {
        $image = $this->images()->first();
        return $image ? $image->image_url : asset('images/no-image.png');
    }

    public function getUrlAttribute()
    {
        return route('products.show', $this->slug);
    }

    public function getIsInStockAttribute()
    {
        return $this->stock_quantity > 0;
    }

    // Methods
    public function isOnSale(): bool
    {
        if (!$this->special_price) {
            return false;
        }

        $now = now();

        if ($this->special_price_from && $now->lt($this->special_price_from)) {
            return false;
        }

        if ($this->special_price_to && $now->gt($this->special_price_to)) {
            return false;
        }

        return true;
    }

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

        // Log inventory change
        InventoryLog::create([
            'product_id' => $this->id,
            'type' => 'sale',
            'quantity' => -$quantity,
            'stock_after' => $this->stock_quantity,
            'reference_type' => 'order',
        ]);
    }

    public function incrementStock(int $quantity): void
    {
        $this->increment('stock_quantity', $quantity);

        InventoryLog::create([
            'product_id' => $this->id,
            'type' => 'restock',
            'quantity' => $quantity,
            'stock_after' => $this->stock_quantity,
        ]);
    }

    public function incrementView(): void
    {
        $this->increment('view_count');

        // Track recently viewed
        if (auth()->check()) {
            RecentlyViewed::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'product_id' => $this->id,
                ],
                [
                    'viewed_at' => now(),
                ]
            );
        }
    }

    public function updateRating(): void
    {
        $this->update([
            'rating' => $this->reviews()->avg('rating') ?? 0,
            'review_count' => $this->reviews()->count(),
        ]);
    }

    public function calculateMlmCommission(int $quantity = 1): float
    {
        if (!$this->is_mlm_product || !$this->mlmPackage) {
            return 0;
        }

        return $this->mlmPackage->calculateCommission($this->final_price, $quantity);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = \Illuminate\Support\Str::slug($product->name);
            }

            if (empty($product->sku)) {
                $product->sku = 'PRD-' . strtoupper(uniqid());
            }
        });

        static::updating(function ($product) {
            // Check stock and update status
            if ($product->stock_quantity <= 0 && $product->status === 'active') {
                $product->status = 'out_of_stock';
            }
        });
    }
}
