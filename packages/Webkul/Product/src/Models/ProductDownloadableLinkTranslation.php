<?php

namespace Webkul\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Product\Database\Factories\ProductDownloadableLinkTranslationFactory;
use Webkul\Product\Contracts\ProductDownloadableLinkTranslation as ProductDownloadableLinkTranslationContract;

class ProductDownloadableLinkTranslation extends Model implements ProductDownloadableLinkTranslationContract
{

    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['title'];

    /**
     * Create a new factory instance for the model.
     *
     * @return ProductDownloadableLinkTranslationFactory
     */
    protected static function newFactory(): ProductDownloadableLinkTranslationFactory
    {
        return ProductDownloadableLinkTranslationFactory::new();
    }

}