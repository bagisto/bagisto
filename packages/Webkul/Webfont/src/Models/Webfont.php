<?php

namespace Webkul\Webfont\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Webfont\Contracts\Webfont as WebfontContract;
use Company;

class Webfont extends Model implements WebfontContract
{
    protected $table = 'google_web_fonts';

    protected $fillable = ['font', 'activated', 'company_id'];

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
            return new \Illuminate\Database\Eloquent\Builder($query->where('google_web_fonts' . '.company_id', $company->id));
        }
    }
}