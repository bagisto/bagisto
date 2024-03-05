<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Webkul\Category\Models\CategoryProxy;
use Webkul\Core\Contracts\Channel as ChannelContract;
use Webkul\Core\Database\Factories\ChannelFactory;
use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Inventory\Models\InventorySourceProxy;

class Channel extends TranslatableModel implements ChannelContract
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
        'description',
        'theme',
        'hostname',
        'default_locale_id',
        'base_currency_id',
        'root_category_id',
        'home_seo',
        'is_maintenance_on',
        'maintenance_mode_text',
        'allowed_ips',
    ];

    /**
     * Castable.
     *
     * @var array
     */
    protected $casts = [
        'home_seo' => 'array',
    ];

    /**
     * Translated attributes.
     *
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'description',
        'maintenance_mode_text',
        'home_seo',
    ];

    /**
     * Get the channel locales.
     */
    public function locales(): BelongsToMany
    {
        return $this->belongsToMany(LocaleProxy::modelClass(), 'channel_locales');
    }

    /**
     * Get the default locale
     */
    public function default_locale(): BelongsTo
    {
        return $this->belongsTo(LocaleProxy::modelClass());
    }

    /**
     * Get the channel locales.
     */
    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(CurrencyProxy::modelClass(), 'channel_currencies');
    }

    /**
     * Get the channel inventory sources.
     */
    public function inventory_sources(): BelongsToMany
    {
        return $this->belongsToMany(InventorySourceProxy::modelClass(), 'channel_inventory_sources');
    }

    /**
     * Get the base currency.
     */
    public function base_currency(): BelongsTo
    {
        return $this->belongsTo(CurrencyProxy::modelClass());
    }

    /**
     * Get the root category.
     */
    public function root_category(): BelongsTo
    {
        return $this->belongsTo(CategoryProxy::modelClass(), 'root_category_id');
    }

    /**
     * Get logo image url.
     */
    public function logo_url()
    {
        if (! $this->logo) {
            return;
        }

        return Storage::url($this->logo);
    }

    /**
     * Get logo image url.
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo_url();
    }

    /**
     * Get favicon image url.
     */
    public function favicon_url()
    {
        if (! $this->favicon) {
            return;
        }

        return Storage::url($this->favicon);
    }

    /**
     * Get favicon image url.
     */
    public function getFaviconUrlAttribute()
    {
        return $this->favicon_url();
    }

    /**
     * Create a new factory instance for the model
     */
    protected static function newFactory(): Factory
    {
        return ChannelFactory::new();
    }
}
