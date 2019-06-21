<?php

namespace Webkul\SAASCustomizer\Observers\Category;

use Webkul\SAASCustomizer\Models\Category\CategoryTranslation;

use Company;

class CategoryTranslationObserver
{
    public function creating(CategoryTranslation $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}