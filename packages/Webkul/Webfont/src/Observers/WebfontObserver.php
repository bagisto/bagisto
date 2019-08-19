<?php

namespace Webkul\Webfont\Observers;

use Webkul\Webfont\Models\Webfont;

use Company;

class WebfontObserver
{
    public function creating(Webfont $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}