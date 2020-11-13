<?php

namespace Webkul\Attribute\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\Attribute\Models\Attribute::class,
        \Webkul\Attribute\Models\AttributeFamily::class,
        \Webkul\Attribute\Models\AttributeGroup::class,
        \Webkul\Attribute\Models\AttributeOption::class,
        \Webkul\Attribute\Models\AttributeOptionTranslation::class,
        \Webkul\Attribute\Models\AttributeTranslation::class,
    ];
}