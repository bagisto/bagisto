<?php

namespace Webkul\Velocity\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
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