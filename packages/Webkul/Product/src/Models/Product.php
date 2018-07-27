<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Category\Models\Category;

class Product extends Model
{
    protected $guarded = ['_token'];

    /**
     * The categories that belong to the product.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }
}