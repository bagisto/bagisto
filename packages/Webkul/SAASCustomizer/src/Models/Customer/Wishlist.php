<?php

namespace Webkul\SAASCustomizer\Models\Customer;

use Webkul\Customer\Models\Wishlist as BaseModel;

use Company;

class Wishlist extends BaseModel
{
    protected $fillable = ['channel_id', 'product_id', 'customer_id', 'item_options','moved_to_cart','shared','time_of_moving', 'company_id'];

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
            return new \Illuminate\Database\Eloquent\Builder($query->where('wishlist' . '.company_id', $company->id));
        }
    }
}