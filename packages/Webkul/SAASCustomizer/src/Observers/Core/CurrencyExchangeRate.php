<?php

namespace Webkul\SAASCustomizer\Observers\Core;

use Webkul\SAASCustomizer\Models\Core\CurrencyExchangeRate;

use Company;

class CurrencyExchangeRateObserver
{
    public function creating(CurrencyExchangeRate $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}