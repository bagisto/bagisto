<?php

namespace Webkul\SAASCustomizer\Models\Attribute;

use Webkul\Attribute\Models\Attribute as BaseModel;

use Company;

class Attribute extends BaseModel
{
    protected $fillable = ['code', 'admin_name', 'type', 'position', 'is_required', 'is_unique', 'validation', 'value_per_locale', 'value_per_channel', 'is_filterable', 'is_configurable', 'is_visible_on_front', 'is_user_defined', 'swatch_type', 'company_id'];

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        $company = Company::getCurrent();

        if (auth()->guard('super-admin')->check() || ! isset($company->id)) {
            return new \Illuminate\Database\Eloquent\Builder($query);
        } else {
            return new \Illuminate\Database\Eloquent\Builder($query->where('attributes' . '.company_id', $company->id));
        }
    }
}