<?php

namespace Webkul\CMS\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\CMS\Contracts\CmsPage as CmsPageContract;
use Webkul\Core\Models\ChannelProxy;

class CmsPage extends TranslatableModel implements CmsPageContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['layout'];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatedAttributes = [
        'content',
        'meta_description',
        'meta_title',
        'page_title',
        'meta_keywords',
        'html_content',
        'url_key',
    ];

    /**
     * With the translations given attributes
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * Get the channels.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany;
     */
    public function channels()
    {
        return $this->belongsToMany(ChannelProxy::modelClass(), 'cms_page_channels');
    }
}