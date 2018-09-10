<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInventory extends Model
{
    public $timestamps = false;

    protected $fillable = ['qty', 'product_id', 'inventory_source_id'];
}