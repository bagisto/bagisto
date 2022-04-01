<?php

namespace Webkul\Sitemap\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sitemap\Contracts\Sitemap as SitemapContract;

class Sitemap extends Model implements SitemapContract
{
    protected $fillable = [
        'file_name',
        'path',
        'generated_at',
    ];
}