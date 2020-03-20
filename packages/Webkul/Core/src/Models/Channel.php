<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Webkul\Category\Models\CategoryProxy;
use Webkul\Inventory\Models\InventorySourceProxy;
use Webkul\Core\Contracts\Channel as ChannelContract;

class Channel extends Model implements ChannelContract
{
    protected $fillable = [
        'code',
        'name',
        'description',
        'theme',
        'home_page_content',
        'footer_content',
        'hostname',
        'default_locale_id',
        'base_currency_id',
        'root_category_id',
        'home_seo',
    ];

    /**
     * Get the channel locales.
     */
    public function locales()
    {
        return $this->belongsToMany(LocaleProxy::modelClass(), 'channel_locales');
    }

    /**
     * Get the default locale
     */
    public function default_locale()
    {
        return $this->belongsTo(LocaleProxy::modelClass());
    }

    /**
     * Get the channel locales.
     */
    public function currencies()
    {
        return $this->belongsToMany(CurrencyProxy::modelClass(), 'channel_currencies');
    }

    /**
     * Get the channel inventory sources.
     */
    public function inventory_sources()
    {
        return $this->belongsToMany(InventorySourceProxy::modelClass(), 'channel_inventory_sources');
    }

    /**
     * Get the base currency
     */
    public function base_currency()
    {
        return $this->belongsTo(CurrencyProxy::modelClass());
    }

    /**
     * Get the base currency
     */
    public function root_category()
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
}