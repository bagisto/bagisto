<?php

namespace Webkul\CMS\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\CMS\Contracts\CMS as CMSContract;

class CMS extends Model implements CMSContract
{
    protected $table = 'cms_pages';

    protected $fillable = ['content', 'url_key', 'layout', 'channel_id', 'locale_id'];
}