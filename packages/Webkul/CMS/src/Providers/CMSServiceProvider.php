<?php

namespace Webkul\CMS\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\CMS\Providers\ModuleServiceProvider;

class CMSServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}