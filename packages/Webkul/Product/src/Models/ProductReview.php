<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Models\CustomerProxy;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Product\Database\Factories\ProductReviewFactory;
use Webkul\Product\Contracts\ProductReview as ProductReviewContract;

class ProductReview extends Model implements ProductReviewContract
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'title',
        'rating',
        'status',
        'product_id',
        'customer_id',
        'name',
    ];

    /**
     * Get the product attribute family that owns the product.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerProxy::modelClass());
    }

    /**
     * Get the product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * The images that belong to the review.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductReviewImageProxy::modelClass(), 'review_id');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ProductReviewFactory::new();
    }
}