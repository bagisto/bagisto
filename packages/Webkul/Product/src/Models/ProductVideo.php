<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\Product\Contracts\ProductVideo as ProductVideoContract;

class ProductVideo extends Model implements ProductVideoContract
{
    /**
     * Timestamps.
     *
     * @param bool
     */
    public $timestamps = false;

    /**
     * Fillable.
     *
     * @param array
     */
    protected $fillable = [
        'type',
        'path',
        'product_id',
        'position',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['url'];

    /**
     * Get the product that owns the image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get image url for the product image.
     *
     * @return string
     */
    public function url()
    {
        return Storage::url($this->path);
    }

    /**
     * Get image url for the product image.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->url();
    }

    /**
     * Is custom attribute.
     *
     * @param  string  $attribute
     * @return bool
     */
    public function isCustomAttribute($attribute)
    {
        return $this->attribute_family->custom_attributes->pluck('code')->contains($attribute);
    }
}
