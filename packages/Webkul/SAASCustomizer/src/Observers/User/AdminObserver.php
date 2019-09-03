<?php

namespace Webkul\SAASCustomizer\Observers\User;

use Webkul\SAASCustomizer\Models\User\Admin;

use Company;

class AdminObserver
{
    public function creating(Admin $model)
    {
        if (Company::getCurrent()) {
            if (! isset($model->company_id)) {
                $model->company_id = Company::getCurrent()->id;
            }
        }
    }
}