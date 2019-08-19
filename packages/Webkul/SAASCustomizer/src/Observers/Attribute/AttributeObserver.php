<?php

namespace Webkul\SAASCustomizer\Observers\Attribute;

use Webkul\SAASCustomizer\Models\Attribute\Attribute;

use Company;

class AttributeObserver
{
    public function creating(Attribute $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->use_in_flat = 0;
            $model->company_id = Company::getCurrent()->id;
        }
    }

    public function updating(Attribute $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->use_in_flat = 0;
        }
    }
}