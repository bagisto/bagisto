<?php

namespace Webkul\Attribute\Providers;

use Webkul\Attribute\Models\Attribute;
use Webkul\Attribute\Models\AttributeFamily;
use Webkul\Attribute\Models\AttributeGroup;
use Webkul\Attribute\Models\AttributeOption;
use Webkul\Attribute\Models\AttributeOptionTranslation;
use Webkul\Attribute\Models\AttributeTranslation;
use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        Attribute::class,
        AttributeFamily::class,
        AttributeGroup::class,
        AttributeOption::class,
        AttributeOptionTranslation::class,
        AttributeTranslation::class,
    ];
}
