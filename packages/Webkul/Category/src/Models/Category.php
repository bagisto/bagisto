<?php

namespace Webkul\Category\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Kalnoy\Nestedset\NodeTrait;
use Shetabit\Visitor\Traits\Visitable;
use Webkul\Attribute\Models\AttributeProxy;
use Webkul\Category\Contracts\Category as CategoryContract;
use Webkul\Category\Database\Factories\CategoryFactory;
use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Product\Models\ProductProxy;

class Category extends TranslatableModel implements CategoryContract
{
    use HasFactory, NodeTrait, Visitable;

    /**
     * Translated attributes.
     *
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'description',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    /**
     * Fillable.
     *
     * @var array
     */
    protected $fillable = [
        'position',
        'status',
        'display_mode',
        'parent_id',
        'additional',
    ];

    /**
     * Eager loading.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * Appends.
     *
     * @var array
     */
    protected $appends = ['logo_url', 'banner_url', 'url'];

    /**
     * The products that belong to the category.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(ProductProxy::modelClass(), 'product_categories');
    }

    /**
     * The filterable attributes that belong to the category.
     */
    public function filterableAttributes(): BelongsToMany
    {
        return $this->belongsToMany(AttributeProxy::modelClass(), 'category_filterable_attributes')
            ->with([
                'options' => function ($query) {
                    $query->orderBy('sort_order');
                },
                'translations',
                'options.translations',
            ]);
    }

    /**
     * Get url attribute.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        if ($categoryTranslation = $this->translate(core()->getCurrentLocale()->code)) {
            return url($categoryTranslation->slug);
        }

        return url($this->translate(core()->getDefaultLocaleCodeFromDefaultChannel())?->slug);
    }

    /**
     * Get image url for the category image.
     *
     * @return string
     */
    public function getLogoUrlAttribute()
    {
        if (! $this->logo_path) {
            return;
        }

        return Storage::url($this->logo_path);
    }

    /**
     * Get banner url attribute.
     *
     * @return string
     */
    public function getBannerUrlAttribute()
    {
        if (! $this->banner_path) {
            return;
        }

        return Storage::url($this->banner_path);
    }

    /**
     * Use fallback for category.
     */
    protected function useFallback(): bool
    {
        return true;
    }

    /**
     * Get fallback locale for category.
     */
    protected function getFallbackLocale(?string $locale = null): ?string
    {
        if ($fallback = core()->getDefaultLocaleCodeFromDefaultChannel()) {
            return $fallback;
        }

        return parent::getFallbackLocale();
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return CategoryFactory::new();
    }
}
