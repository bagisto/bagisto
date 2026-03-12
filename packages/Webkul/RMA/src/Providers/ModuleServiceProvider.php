<?php

namespace Webkul\RMA\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Webkul\RMA\Models\RMA;
use Webkul\RMA\Models\RMAAdditionalField;
use Webkul\RMA\Models\RMACustomField;
use Webkul\RMA\Models\RMACustomFieldOption;
use Webkul\RMA\Models\RMAImage;
use Webkul\RMA\Models\RMAItem;
use Webkul\RMA\Models\RMAMessage;
use Webkul\RMA\Models\RMAReason;
use Webkul\RMA\Models\RMAReasonResolution;
use Webkul\RMA\Models\RMARule;
use Webkul\RMA\Models\RMAStatus;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Define the models provided by this module
     */
    protected $models = [
        RMA::class,
        RMAAdditionalField::class,
        RMACustomField::class,
        RMACustomFieldOption::class,
        RMAImage::class,
        RMAItem::class,
        RMAMessage::class,
        RMAReason::class,
        RMAReasonResolution::class,
        RMARule::class,
        RMAStatus::class,
    ];
}
