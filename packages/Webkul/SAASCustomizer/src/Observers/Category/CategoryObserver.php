<?php

namespace Webkul\SAASCustomizer\Observers\Category;

use Webkul\SAASCustomizer\Models\Category\Category;

use Company;

class CategoryObserver
{
    public function creating(Category $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}