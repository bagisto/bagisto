<?php

namespace Webkul\Core\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Webkul\Core\Helpers\Locales;

class TranslatableModel extends Model
{
    use Translatable;

    /**
     * Get locales helper.
     *
     * @return \Webkul\Core\Helpers\Locales
     */
    protected function getLocalesHelper(): Locales
    {
        return app(Locales::class);
    }
}
