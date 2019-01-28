<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFlat extends Model
{
    protected $table = 'product_flat';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public $timestamps = false;

    /**
     * Get the product that owns the attribute value.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}