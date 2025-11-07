<?php

namespace Webkul\Stripe\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Stripe\Models\StripeCart::class,
        \Webkul\Stripe\Models\Stripe::class,
    ];
}
