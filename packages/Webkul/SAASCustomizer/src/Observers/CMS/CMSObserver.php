<?php

namespace Webkul\SAASCustomizer\Observers\CMS;

use Webkul\SAASCustomizer\Models\CMS\CMS;

use Company;

class CMSObserver
{
    public function creating(CMS $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}