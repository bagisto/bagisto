<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Customer\Models\Customer;

class ProductReview extends Model
{
    protected $fillable = [];

    /**
     * Get the product attribute family that owns the product.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}