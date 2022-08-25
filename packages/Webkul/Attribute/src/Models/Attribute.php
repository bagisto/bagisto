<?php

namespace Webkul\Attribute\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Core\Eloquent\TranslatableModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\Attribute\Database\Factories\AttributeFactory;
use Webkul\Attribute\Contracts\Attribute as AttributeContract;

class Attribute extends TranslatableModel implements AttributeContract
{
    use HasFactory;

    public $translatedAttributes = ['name'];

    protected $fillable = [
        'code',
        'admin_name',
        'type',
        'enable_wysiwyg',
        'position',
        'is_required',
        'is_unique',
        'validation',
        'value_per_locale',
        'value_per_channel',
        'is_filterable',
        'is_configurable',
        'is_visible_on_front',
        'is_user_defined',
        'swatch_type',
        'use_in_flat',
        'is_comparable',
    ];

    // protected $with = ['options'];

    /**
     * Get the options.
     */
    public function options(): HasMany
    {
        return $this->hasMany(AttributeOptionProxy::modelClass());
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterableAttributes(Builder $query): Builder
    {
        return $query->where('is_filterable', 1)
            ->where('swatch_type', '<>', 'image')
            ->orderBy('position');
    }

    /**
     * Create a new factory instance for the model
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return AttributeFactory::new();
    }
}