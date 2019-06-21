<?php

namespace Webkul\SAASCustomizer\Observers\Core;

use Webkul\SAASCustomizer\Models\Core\CoreConfig;

use Company;

class CoreConfigObserver
{
    public function creating(CoreConfig $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}