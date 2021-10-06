<?php

namespace Webkul\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Category\Contracts\CategoryTranslation as CategoryTranslationContract;

/**
 * Class CategoryTranslation
 *
 * @package Webkul\Category\Models
 *
 * @property-read string $url_path maintained by database triggers
 */
class CategoryTranslation extends Model implements CategoryTranslationContract
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'locale_id',
    ];
}