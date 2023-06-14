<?php

namespace Webkul\Core\Helpers;

use Astrotomic\Translatable\Locales as BaseLocales;

class Locales extends BaseLocales
{
    /**
     * Load.
     *
     * @return void
     */
    public function load(): void
    {
        $this->locales = [];

        foreach (core()->getAllLocales() as $locale) {
            $this->locales[$locale->code] = $locale->code;
        }
    }
}
