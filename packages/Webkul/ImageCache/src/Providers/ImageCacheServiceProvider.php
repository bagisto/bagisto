<?php

namespace Webkul\ImageCache\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\ImageCache\Http\Controllers\ImageCacheController;

class ImageCacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        include __DIR__.'/../Http/helpers.php';

        $this->mergeConfigFrom(__DIR__.'/../Config/imagecache.php', 'imagecache');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->bootImageCache();
    }

    /**
     * Bootstrap the image cache routes, under the shop theme middleware so a missing image gets the channel theme's error page.
     */
    protected function bootImageCache(): void
    {
        if (is_string(config('imagecache.route'))) {
            $filenamePattern = '[ \w\\.\\/\\-\\@\(\)\=]+';

            $this->app['router']
                ->get(config('imagecache.route').'/{template}/{filename}', [ImageCacheController::class, 'getResponse'])
                ->name('imagecache')
                ->middleware('theme')
                ->where(['filename' => $filenamePattern]);
        }
    }
}
