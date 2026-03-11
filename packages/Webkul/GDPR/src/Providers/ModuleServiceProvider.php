<?php

namespace Webkul\GDPR\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\GDPR\Models\GDPRDataRequest;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        GDPRDataRequest::class,
    ];
}
