<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Contracts\Slider as SliderContract;

class Slider extends Model implements SliderContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'sliders';

    protected $fillable = [
        'title', 'path', 'content', 'channel_id'
    ];

    /**
     * Get image url for the category image.
     */
    public function image_url()
    {
        if (! $this->path) {
            return;
        }

        return Storage::url($this->path);
    }

    /**
     * Get image url for the category image.
     */
    public function getImageUrlAttribute()
    {
        return $this->image_url();
    }
}