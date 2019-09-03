<?php

namespace Webkul\SAASCustomizer\Models\Category;

use Webkul\Category\Models\CategoryTranslation as BaseModel;

use Company;

class CategoryTranslation extends BaseModel
{
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        // $company = Company::getCurrent();

        return new \Webkul\SAASCustomizer\Database\Eloquent\Builder($query);

        // if ($company != null) {
        //     return new \Illuminate\Database\Eloquent\Builder($query->where('category_translations' . '.company_id', $company->id));
        // } else {
        //     return new \Illuminate\Database\Eloquent\Builder($query->where('category_translations' . '.company_id', null));
        // }
    }
}