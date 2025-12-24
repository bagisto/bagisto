<?php

namespace Webkul\RMA\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Define the models provided by this module
     */
    protected $models = [
        \Webkul\RMA\Models\RMA::class,
        \Webkul\RMA\Models\RMAAdditionalField::class,
        \Webkul\RMA\Models\RMACustomField::class,
        \Webkul\RMA\Models\RMACustomFieldOption::class,
        \Webkul\RMA\Models\RMAImage::class,
        \Webkul\RMA\Models\RMAItem::class,
        \Webkul\RMA\Models\RMAMessage::class,
        \Webkul\RMA\Models\RMAReason::class,
        \Webkul\RMA\Models\RMAReasonResolution::class,
        \Webkul\RMA\Models\RMARule::class,
        \Webkul\RMA\Models\RMAStatus::class,
    ];
}
