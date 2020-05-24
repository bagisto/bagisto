<?php

namespace Webkul\Customer\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Customer\Models\Customer::class,
        \Webkul\Customer\Models\CustomerAddress::class,
        \Webkul\Customer\Models\CustomerGroup::class,
        \Webkul\Customer\Models\Wishlist::class,
    ];
}