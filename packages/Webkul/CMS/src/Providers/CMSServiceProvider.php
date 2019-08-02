<?php

namespace Webkul\CMS\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Webkul\CMS\Providers\ModuleServiceProvider;

class CMSServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'cms');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}