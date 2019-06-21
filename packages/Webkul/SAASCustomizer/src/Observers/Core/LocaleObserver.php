<?php

namespace Webkul\SAASCustomizer\Observers\Core;

use Webkul\SAASCustomizer\Models\Core\Locale;

use Company;

class LocaleObserver
{
    public function creating(Locale $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            if ($model->count() == 0) {
                $model->company_id = Company::getCurrent()->id;
            } else {
                session()->flash('error', 'Creating locale other than English is not allowed');

                abort(404);
            }
        }
    }

    public function deleting(Locale $model)
    {
        if ($model->count() == 1) {
            session()->flash('error', 'Cannot delete the Locale');
        }

        return false;
    }
}