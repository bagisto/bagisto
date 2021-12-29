<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Contracts\Locale as LocaleContract;
use Webkul\Core\Database\Factories\LocaleFactory;

class Locale extends Model implements LocaleContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'direction',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['image_url'];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return LocaleFactory::new ();
    }

    /**
     * Get image url for the logo image.
     *
     * @return string
     */
    public function image_url()
    {
        if (! $this->locale_image) {
            return '';
        }

        return Storage::url($this->locale_image);
    }

    /**
     * Get image url for the logo image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return $this->image_url();
    }
}
