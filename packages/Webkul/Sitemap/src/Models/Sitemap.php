<?php

namespace Webkul\Sitemap\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\Marketing\Database\Factories\SitemapFactory;
use Webkul\Sitemap\Contracts\Sitemap as SitemapContract;

class Sitemap extends Model implements SitemapContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'additional',
        'file_name',
        'generated_at',
        'path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'additional' => 'json',
    ];

    /**
     * Delete the sitemap from storage.
     */
    public function deleteFromStorage(): void
    {
        if ($this->additional) {
            if (! empty($this->additional['sitemaps'])) {
                collect($this->additional['sitemaps'])->each(function ($sitemapUrl) {
                    if (Storage::exists($sitemapUrl)) {
                        Storage::delete($sitemapUrl);
                    }
                });
            }

            if (! empty($this->additional['index'])) {
                if (Storage::exists($sitemapIndexUrl = $this->additional['index'])) {
                    Storage::delete($sitemapIndexUrl);
                }
            }
        }
    }

    /**
     * Get the sitemap index file name.
     */
    public function getIndexFileNameAttribute()
    {
        return clean_path($this->path.'/'.$this->file_name);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return SitemapFactory::new();
    }
}
