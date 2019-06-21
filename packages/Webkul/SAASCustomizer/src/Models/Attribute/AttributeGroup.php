<?php

namespace Webkul\SAASCustomizer\Models\Attribute;

use Webkul\Attribute\Models\AttributeGroup as BaseModel;

use Company;

class AttributeGroup extends BaseModel
{
    protected $fillable = ['name', 'position', 'is_user_defined', 'company_id', 'attribute_family_id'];

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
            return new \Illuminate\Database\Eloquent\Builder($query->where('attribute_groups' . '.company_id', $company->id));
        }
    }
}