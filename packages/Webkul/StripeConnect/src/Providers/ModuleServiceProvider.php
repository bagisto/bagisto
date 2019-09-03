<?php

namespace Webkul\StripeConnect\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\StripeConnect\Models\StripeCart::class,
        \Webkul\StripeConnect\Models\StripeConnect::class,
    ];
}