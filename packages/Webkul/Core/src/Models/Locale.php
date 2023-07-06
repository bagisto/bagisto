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
     * List of all default locale images.
     *
     * @var array
     */
    protected $defaultImages = [
        'de' => 'locale/de.png',
        'en' => 'locale/en.png',
        'es' => 'locale/es.png',
        'fr' => 'locale/fr.png',
        'nl' => 'locale/nl.png',
        'tr' => 'locale/tr.png',
    ];

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
    protected $appends = ['logo_full_path'];

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
    public function getlogoFullPathAttribute(): string
    {
        return $this->logoFullPath();
    }

    /**
     * Get image url for the logo image.
     *
     * @return string
     */
    public function logoFullPath(): string
    {
        if (! $this->logo_path) {
            return $this->getDefaultLogoSource();
        }

        return Storage::url($this->logo_path);
    }

    /**
     * Get default image source.
     *
     * @return string
     */
    public function getDefaultLogoSource(): string
    {
        return isset($this->defaultImages[$this->code]) && file_exists($this->defaultImages[$this->code])
            ? Storage::url($this->defaultImages[$this->code])
            : '';
    }
}
