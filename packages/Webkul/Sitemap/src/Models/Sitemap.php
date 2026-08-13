<?php

namespace Webkul\Sitemap\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Models\ChannelProxy;
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
     * Get the channels.
     *
     * @return BelongsToMany
     */
    public function channels()
    {
        return $this->belongsToMany(ChannelProxy::modelClass(), 'sitemap_channels', 'sitemap_id');
    }

    /**
     * Delete the sitemap from storage.
     *
     * Supports the current per-channel shape stored under additional.channels as well as
     * the legacy flat additional.{index,sitemaps} shape, so old records still clean up.
     */
    public function deleteFromStorage(): void
    {
        if (! $this->additional) {
            return;
        }

        $disk = Storage::disk('public');

        foreach ($this->additional['channels'] ?? [] as $channel) {
            foreach ($channel['sitemaps'] ?? [] as $path) {
                if ($disk->exists($path)) {
                    $disk->delete($path);
                }
            }

            if (! empty($channel['index']) && $disk->exists($channel['index'])) {
                $disk->delete($channel['index']);
            }
        }

        foreach ($this->additional['sitemaps'] ?? [] as $path) {
            if ($disk->exists($path)) {
                $disk->delete($path);
            }
        }

        if (! empty($this->additional['index']) && $disk->exists($this->additional['index'])) {
            $disk->delete($this->additional['index']);
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
