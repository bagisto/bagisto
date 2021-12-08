<?php

namespace Webkul\Core\Models;

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
        'is_maintenance_on',
        'maintenance_mode_text',
        'allowed_ips',
    ];

    public array $translatedAttributes = [
        'name',
        'description',
        'home_page_content',
        'footer_content',
        'maintenance_mode_text',
        'home_seo',
    ];

	/**
	 * Get the channel locales.
	 *
	 * @return BelongsToMany
	 */
    public function locales(): BelongsToMany
    {
        return $this->belongsToMany(LocaleProxy::modelClass(), 'channel_locales');
    }

	/**
	 * Get the default locale
	 *
	 * @return BelongsTo
	 */
    public function default_locale(): BelongsTo
    {
        return $this->belongsTo(LocaleProxy::modelClass());
    }

	/**
	 * Get the channel locales.
	 *
	 * @return BelongsToMany relationship
	 */
    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(CurrencyProxy::modelClass(), 'channel_currencies');
    }

	/**
	 * Get the channel inventory sources.
	 *
	 * @return BelongsToMany relationship
	 */
    public function inventory_sources(): BelongsToMany
    {
        return $this->belongsToMany(InventorySourceProxy::modelClass(), 'channel_inventory_sources');
    }

	/**
	 * Get the base currency
	 *
	 * @return BelongsTo relationship
	 */
    public function base_currency(): BelongsTo
    {
        return $this->belongsTo(CurrencyProxy::modelClass());
    }

	/**
	 * Get the base currency
	 *
	 * @return BelongsTo relationship
	 */
    public function root_category(): BelongsTo
    {
        return $this->belongsTo(CategoryProxy::modelClass(), 'root_category_id');
    }

	/**
	 * Get logo image url.
	 *
	 * @return null|string
	 */
    public function logo_url(): ?string
    {
        if (! $this->logo) {
            return null;
        }

        return Storage::url($this->logo);
    }

	/**
	 * Get logo image url.
	 *
	 * @return null|string
	 */
    public function getLogoUrlAttribute(): ?string
	{
        return $this->logo_url();
    }

	/**
	 * Get favicon image url.
	 *
	 * @return null|string
	 */
	public function favicon_url(): ?string
    {
        if (! $this->favicon) {
            return null;
        }

        return Storage::url($this->favicon);
    }

	/**
	 * Get favicon image url.
	 *
	 * @return null|string
	 */
    public function getFaviconUrlAttribute(): ?string
	{
        return $this->favicon_url();
    }

	/**
	 * Create a new factory instance for the model
	 *
	 * @return ChannelFactory
	 */
    protected static function newFactory(): ChannelFactory
    {
        return ChannelFactory::new();
    }
}
