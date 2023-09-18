<?php

namespace Webkul\Shop\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\Shop\Models\ThemeCustomization::class,
        \Webkul\Shop\Models\ThemeCustomizationChannels::class,
        \Webkul\Shop\Models\ThemeCustomizationTranslation::class,
    ];
}