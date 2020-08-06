<?php

namespace Webkul\SocialLogin\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\SocialLogin\Models\CustomerSocialAccount::class,
    ];
}