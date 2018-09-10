<?php

namespace Webkul\Attribute\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Attribute\Models\AttributeGroup;

class Attribute extends TranslatableModel
{
    public $translatedAttributes = ['name'];

    protected $fillable = ['code', 'admin_name', 'type', 'position', 'is_required', 'is_unique', 'value_per_locale', 'value_per_channel', 'is_filterable', 'is_configurable', 'is_visible_on_front'];

    protected $with = ['options'];

    /**
     * Get the options.
     */
    public function options()
    {
        return $this->hasMany(AttributeOption::class);
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterableAttributes($query)
    {
        return $query->where('is_filterable', 1)->orderBy('position');
    }
}