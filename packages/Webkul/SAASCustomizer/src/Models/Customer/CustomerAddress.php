<?php
namespace Webkul\SAASCustomizer\Models\Customer;

use Webkul\Customer\Models\CustomerAddress as BaseModel;

use Company;

class CustomerAddress extends BaseModel
{
    protected $fillable = ['customer_id' ,'address1', 'country', 'state', 'city', 'postcode', 'phone', 'default_address', 'company_id'];

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
            return new \Illuminate\Database\Eloquent\Builder($query->where('customer_addresses' . '.company_id', $company->id));
        }
    }
}