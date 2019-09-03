<?php

namespace Webkul\SAASCustomizer\Models\Attribute;

use Webkul\Attribute\Models\AttributeTranslation as BaseModel;

class AttributeTranslation extends BaseModel
{
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new \Illuminate\Database\Eloquent\Builder($query);
    }
}