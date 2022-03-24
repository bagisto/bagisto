<?php

namespace Webkul\Velocity\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\Velocity\Models\Category::class,
        \Webkul\Velocity\Models\Content::class,
        \Webkul\Velocity\Models\ContentTranslation::class,
        \Webkul\Velocity\Models\OrderBrand::class,
        \Webkul\Velocity\Models\VelocityCustomerCompareProduct::class,
        \Webkul\Velocity\Models\VelocityMetadata::class,
    ];
}