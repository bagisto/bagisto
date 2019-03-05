<?php

namespace Webkul\Attribute\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Attribute\Contracts\Attribute as AttributeContract;

class Attribute extends TranslatableModel implements AttributeContract
{
    public $translatedAttributes = ['name'];

    protected $fillable = ['code', 'admin_name', 'type', 'position', 'is_required', 'is_unique', 'validation', 'value_per_locale', 'value_per_channel', 'is_filterable', 'is_configurable', 'is_visible_on_front', 'is_user_defined', 'swatch_type'];

    protected $with = ['options'];

    /**
     * Get the options.
     */
    public function options()
    {
        return $this->hasMany(AttributeOptionProxy::modelClass());
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterableAttributes($query)
    {
        return $query->where('is_filterable', 1)->where('swatch_type', '<>', 'image')->orderBy('position');
    }
}