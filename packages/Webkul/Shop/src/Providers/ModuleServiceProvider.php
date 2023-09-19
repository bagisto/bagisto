<?php

namespace Webkul\Shop\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Define the models
     *
     * @var array
     */
    protected $models = [
        \Webkul\Shop\Models\ThemeCustomization::class,
        \Webkul\Shop\Models\ThemeCustomizationTranslation::class,
    ];
}