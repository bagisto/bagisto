<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Contracts\ProductBundleOptionTranslation as ProductBundleOptionTranslationContract;

class ProductBundleOptionTranslation extends Model implements ProductBundleOptionTranslationContract
{
    public $timestamps = false;

    protected $fillable = ['label'];
}