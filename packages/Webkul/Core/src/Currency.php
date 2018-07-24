<?php

namespace Webkul\Core;

use Webkul\Core\Models\Locale as LocaleModel;

class Locale
{
    public function all() {
        return LocaleModel::all();
    }
}