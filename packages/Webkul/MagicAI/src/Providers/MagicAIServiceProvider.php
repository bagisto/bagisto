<?php

namespace Webkul\MagicAI\Providers;

use Illuminate\Support\ServiceProvider;

class MagicAIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        include __DIR__.'/../Http/helpers.php';
    }
}
