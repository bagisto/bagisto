<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['_token'];
}