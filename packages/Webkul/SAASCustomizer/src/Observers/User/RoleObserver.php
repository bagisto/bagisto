<?php

namespace Webkul\SAASCustomizer\Observers\User;

use Webkul\SAASCustomizer\Models\User\Role;

use Company;

class RoleObserver
{
    public function creating(Role $model)
    {
        if (Company::getCurrent()) {
            if (! isset($model->company_id)) {
                $model->company_id = Company::getCurrent()->id;
            }
        }
    }
}