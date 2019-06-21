<?php

namespace Webkul\SAASCustomizer\Models\Product;

use Webkul\Product\Models\ProductOrderedInventory as BaseModel;

use Company;

class ProductOrderedInventory extends BaseModel
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