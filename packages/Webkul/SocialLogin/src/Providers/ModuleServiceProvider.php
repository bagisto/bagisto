<?php

namespace Webkul\SocialLogin\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\SocialLogin\Models\CustomerSocialAccount;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        CustomerSocialAccount::class,
    ];
}
