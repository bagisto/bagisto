<?php

namespace Webkul\Checkout\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\Checkout\Contracts\CartItem as CartItemContract;
use Webkul\Checkout\Database\Factories\CartItemFactory;
use Webkul\Product\Models\ProductProxy;
use Webkul\Product\Type\AbstractType;

class CartItem extends Model implements CartItemContract
{
    use HasFactory;

    protected $table = 'cart_items';

    protected $casts = [
        'additional' => 'array',
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $typeInstance;

    /**
     * Retrieve type instance
     */
    public function getTypeInstance(): AbstractType
    {
        if ($this->typeInstance) {
            return $this->typeInstance;
        }

        $this->typeInstance = app(config('product_types.'.$this->type.'.class'));

        if ($this->product) {
            $this->typeInstance->setProduct($this->product);
        }

        return $this->typeInstance;
    }

    public function product(): HasOne
    {
        return $this->hasOne(ProductProxy::modelClass(), 'id', 'product_id');
    }

    public function cart(): HasOne
    {
        return $this->hasOne(CartProxy::modelClass(), 'id', 'cart_id');
    }

    /**
     * Get the child item.
     */
    public function child(): BelongsTo
    {
        return $this->belongsTo(static::class, 'id', 'parent_id');
    }

    /**
     * Get the parent item record associated with the cart item.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the children items.
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Create a new factory instance for the model
     */
    protected static function newFactory(): Factory
    {
        return CartItemFactory::new();
    }
}
