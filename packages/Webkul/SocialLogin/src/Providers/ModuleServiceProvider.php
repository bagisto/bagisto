<?php

namespace Webkul\SocialLogin\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Webkul\SocialLogin\Models\CustomerSocialAccount::class,
    ];
}
