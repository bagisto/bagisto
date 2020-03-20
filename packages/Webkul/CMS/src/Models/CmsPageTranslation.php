<?php

namespace Webkul\CMS\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\CMS\Contracts\CmsPageTranslation as CmsPageTranslationContract;

class CmsPageTranslation extends Model implements CmsPageTranslationContract
{
    public $timestamps = false;

    protected $fillable = [
        'page_title',
        'url_key',
        'html_content',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'locale',
        'cms_page_id',
    ];
}