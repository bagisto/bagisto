<?php

namespace Webkul\ImageCache\Providers;

use Illuminate\Support\ServiceProvider;

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
     * Bootstrap the image cache routes.
     */
    protected function bootImageCache(): void
    {
        if (is_string(config('imagecache.route'))) {
            $filenamePattern = '[ \w\\.\\/\\-\\@\(\)\=]+';

            $this->app['router']->get(config('imagecache.route').'/{template}/{filename}', [
                'uses' => 'Webkul\ImageCache\Http\Controllers\ImageCacheController@getResponse',
                'as' => 'imagecache',
            ])->where(['filename' => $filenamePattern]);
        }
    }
}
