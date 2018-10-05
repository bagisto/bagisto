<?php

namespace Webkul\Customer\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $table = 'wishlist';

    protected $fillable = ['channel_id', 'product_id', 'customer_id', 'item_options','moved_to_cart','shared','time_of_moving'];
}
