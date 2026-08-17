<?php

namespace Webkul\EUWithdrawal\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;
use Webkul\EUWithdrawal\Models\Withdrawal;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models registered with Concord for this module.
     *
     * @var class-string[]
     */
    protected $models = [
        Withdrawal::class,
    ];
}
