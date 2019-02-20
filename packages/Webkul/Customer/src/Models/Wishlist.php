<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\ProductProxy;
use Webkul\Customer\Contracts\Wishlist as WishlistContract;

class Wishlist extends Model implements WishlistContract
{
    protected $table = 'wishlist';

    protected $fillable = ['channel_id', 'product_id', 'customer_id', 'item_options','moved_to_cart','shared','time_of_moving'];

    public function product() {
        return $this->hasOne(ProductProxy::modelClass(), 'id', 'product_id');
    }
}
