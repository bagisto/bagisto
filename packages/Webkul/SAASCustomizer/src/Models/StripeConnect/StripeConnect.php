<?php

namespace Webkul\SAASCustomizer\Models\StripeConnect;

use Webkul\StripeConnect\Models\StripeConnect as BaseModel;

use Company;

class StripeConnect extends BaseModel
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
            return new \Illuminate\Database\Eloquent\Builder($query);
        } else {
            return new \Illuminate\Database\Eloquent\Builder($query->where('stripe_companies' . '.company_id', $company->id));
        }
    }
}