<?php

namespace Webkul\Custom\Models;

use Webkul\Core\Models\Slider as BaseModel;

use Company;

class Slider extends BaseModel
{
    protected $fillable = [
        'title', 'path', 'content', 'channel_id', 'url_type', 'url_key'
    ];

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
            return new \Illuminate\Database\Eloquent\Builder($query->where('sliders' . '.company_id', $company->id));
        }
    }
}