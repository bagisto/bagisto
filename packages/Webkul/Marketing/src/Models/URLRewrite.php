<?php

namespace Webkul\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketing\Contracts\URLRewrite as URLRewriteContract;

class URLRewrite extends Model implements URLRewriteContract
{
    protected $table = 'url_rewrites';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entity_type',
        'request_path',
        'target_path',
        'redirect_type',
        'locale',
    ];
}
