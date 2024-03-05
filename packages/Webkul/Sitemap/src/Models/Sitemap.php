<?php

namespace Webkul\Sitemap\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Marketing\Database\Factories\SitemapFactory;
use Webkul\Sitemap\Contracts\Sitemap as SitemapContract;

class Sitemap extends Model implements SitemapContract
{
    use HasFactory;

    /**
     * Define the fillable properties
     *
     * @var array
     */
    protected $fillable = [
        'file_name',
        'path',
        'generated_at',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return SitemapFactory::new();
    }
}
