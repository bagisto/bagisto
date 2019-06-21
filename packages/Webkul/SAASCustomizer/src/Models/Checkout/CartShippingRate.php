<?php

namespace Webkul\SAASCustomizer\Models\Checkout;

use Webkul\Checkout\Models\CartShippingRate as BaseModel;

class CartShippingRate extends BaseModel
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