<?php

namespace Webkul\CMS\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\CMS\Contracts\PageTranslation as PageTranslationContract;

class PageTranslation extends Model implements PageTranslationContract
{
    /**
     * Table associated with the model.
     *
     * @var string
     */
    protected $table = 'cms_page_translations';

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
