<?php

namespace Webkul\SAASCustomizer\Observers\Core;

use Webkul\SAASCustomizer\Models\Core\SubscribersList;

use Company;

class SubscribersListObserver
{
    public function creating(SubscribersList $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}