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
                session()->flash('error', trans('saas::app.custom-errors.locale-creation'));

                abort(404);
            }
        }
    }

    public function deleting(Locale $model)
    {
        if ($model->count() == 1) {
            session()->flash('error', trans('saas::app.custom-errors.locale-delete'));
        }

        return false;
    }
}