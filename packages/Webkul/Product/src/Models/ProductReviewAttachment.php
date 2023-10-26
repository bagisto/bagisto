<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Contracts\ProductReviewAttachment as ProductReviewAttachmentContract;

class ProductReviewAttachment extends Model implements ProductReviewAttachmentContract
{
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
}
