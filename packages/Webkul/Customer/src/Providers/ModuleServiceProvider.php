<?php

namespace Webkul\Customer\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\Customer\Models\Customer::class,
        \Webkul\Customer\Models\CustomerAddress::class,
        \Webkul\Customer\Models\CustomerGroup::class,
        \Webkul\Customer\Models\Wishlist::class,
        \Webkul\Customer\Models\CompareItem::class,
        \Webkul\Customer\Models\CustomerNote::class,
    ];
}
