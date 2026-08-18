<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Product\Contracts\ProductImage as ProductImageContract;

class ProductImage extends TranslatableModel implements ProductImageContract
{
    /**
     * Timestamp.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Translated attributes.
     *
     * @var array
     */
    public $translatedAttributes = ['alt_text'];

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'path',
        'product_id',
        'position',
    ];

    /**
     * Eager loading.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['url', 'file_name'];

    /**
     * The attributes hidden from the model's array form. The translated `alt_text` is
     * still exposed, only the raw translation rows are kept out.
     *
     * @var array
     */
    protected $hidden = ['translations'];

    /**
     * Get the product that owns the image.
     *
     * @return BelongsTo
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
     * Get the file name, without the directory and the extension, for the product image.
     *
     * @return string
     */
    public function getFileNameAttribute()
    {
        return pathinfo((string) $this->path, PATHINFO_FILENAME);
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
