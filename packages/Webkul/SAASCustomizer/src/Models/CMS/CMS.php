<?php

namespace Webkul\SAASCustomizer\Models\CMS;

use Webkul\CMS\Models\CMS as BaseModel;

use Company;

class CMS extends BaseModel
{
    protected $fillable = ['url_key', 'html_content', 'page_title', 'meta_title', 'meta_description', 'meta_keywords', 'content', 'channel_id', 'locale_id', 'company_id'];

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
            return new \Illuminate\Database\Eloquent\Builder($query->where('cms_pages' . '.company_id', $company->id));
        }
    }
}