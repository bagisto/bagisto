<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Contracts\ProductImageTranslation as ProductImageTranslationContract;

class ProductImageTranslation extends Model implements ProductImageTranslationContract
{
    /**
     * Timestamp.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = ['alt_text'];
}
