<?php

namespace Webkul\CustomerDocument\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\CustomerDocument\Contracts\CustomerDocument as CustomerDocumentContract;
use Company;

class CustomerDocument extends Model implements CustomerDocumentContract
{
    protected $table = 'customer_documents';

    protected $fillable = ['name', 'path', 'customer_id', 'description', 'type', 'status', 'company_id'];

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
            return new \Illuminate\Database\Eloquent\Builder($query->where('customer_documents' . '.company_id', $company->id));
        }
    }
}