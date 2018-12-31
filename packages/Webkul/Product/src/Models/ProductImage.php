<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    public $timestamps = false;

    protected $fillable = ['path', 'product_id'];

    /**
     * Get the product that owns the image.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get image url for the product image.
     */
    public function url()
    {
        return Storage::url($this->path);
    }

    /**
     * Get image url for the product image.
     */
    public function getUrlAttribute()
    {
        return $this->url();
    }
    
    /**
     * @param string $key
     *
     * @return bool
     */
    public function isCustomAttribute($attribute)
    {
        return $this->attribute_family->custom_attributes->pluck('code')->contains($attribute);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        $array['url'] = $this->url;

        return $array;
    }
}