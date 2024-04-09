<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Contracts\ProductReviewAttachment as ProductReviewAttachmentContract;
use Webkul\Product\Database\Factories\ProductReviewAttachmentFactory;

class ProductReviewAttachment extends Model implements ProductReviewAttachmentContract
{
    use HasFactory;

    /**
     * Timestamp false
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Define fillable property
     *
     * @var array
     */
    protected $fillable = [
        'path',
        'review_id',
        'type',
        'mime_type',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['url'];

    /**
     * Get the review that owns the image.
     */
    public function review(): BelongsTo
    {
        return $this->belongsTo(ProductReviewProxy::modelClass());
    }

    /**
     * Get image url for the review image.
     */
    public function url(): string
    {
        return Storage::url($this->path);
    }

    /**
     * Get image url for the review image.
     */
    public function getUrlAttribute(): string
    {
        return $this->url();
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return ProductReviewAttachmentFactory::new();
    }
}
