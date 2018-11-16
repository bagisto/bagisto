<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\Product;

class Wishlist extends Model
{
    protected $table = 'wishlist';

    protected $fillable = ['channel_id', 'product_id', 'customer_id', 'item_options','moved_to_cart','shared','time_of_moving'];

    public function product() {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
