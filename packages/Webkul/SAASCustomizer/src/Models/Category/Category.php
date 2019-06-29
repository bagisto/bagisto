<?php

namespace Webkul\SAASCustomizer\Models\Category;

use Webkul\Category\Models\Category as BaseModel;
use Kalnoy\Nestedset\QueryBuilder;

use Company;

class Category extends BaseModel
{
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
            return new QueryBuilder($query);
        } else {
            return new QueryBuilder($query->where('company_id', $company->id));
        }
    }
}