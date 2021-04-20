<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Contracts\ProductReviewImage as ProductReviewImageContract;

class ProductReviewImage extends Model implements ProductReviewImageContract
{
    public $timestamps = false;

    protected $fillable = [
        'path',
        'review_id',
    ];

    /**
     * Get the review that owns the image.
     */
    public function review()
    {
        return $this->belongsTo(ProductReviewProxy::modelClass());
    }

    /**
     * Get image url for the review image.
     */
    public function url()
    {
        return Storage::url($this->path);
    }

    /**
     * Get image url for the review image.
     */
    public function getUrlAttribute()
    {
        return $this->url();
    }
}