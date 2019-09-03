<?php

namespace Webkul\SAASCustomizer\Observers\Attribute;

use Webkul\SAASCustomizer\Models\Attribute\AttributeGroup;

use Company;

class AttributeGroupObserver
{
    public function creating(AttributeGroup $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}