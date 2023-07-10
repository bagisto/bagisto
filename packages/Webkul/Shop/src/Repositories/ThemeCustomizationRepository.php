<?php

namespace Webkul\Shop\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Shop\Contracts\ThemeCustomization;

class ThemeCustomizationRepository extends Repository
{
    /**
     * Specify model class name.
     *
     * @return string
     */
    public function model(): string
    {
        return ThemeCustomization::class;
    }
}
?>