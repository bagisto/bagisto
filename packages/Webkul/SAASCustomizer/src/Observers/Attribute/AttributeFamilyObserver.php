<?php

namespace Webkul\SAASCustomizer\Observers\Attribute;

use Webkul\SAASCustomizer\Models\Attribute\AttributeFamily;

use Company;

class AttributeFamilyObserver
{
    public function creating(AttributeFamily $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}