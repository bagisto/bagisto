<?php

namespace Webkul\Core;

use Webkul\Core\Models\Locale as LocaleModel;
use Webkul\Core\Models\Currency as CurrencyModel;

class Core
{
    public function getAllLocales() {
        return LocaleModel::all();
    }

    public function allCurrencies() {
        return CurrencyModel::all();
    }
}