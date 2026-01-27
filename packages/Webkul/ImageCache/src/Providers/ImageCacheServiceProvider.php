<?php

namespace Webkul\ImageCache\Providers;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Intervention\Image\ImageManager;

class ImageCacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        include __DIR__.'/../Http/helpers.php';

        $this->mergeConfigFrom(__DIR__.'/../Config/imagecache.php', 'imagecache');

        $this->app->singleton('image_manager', function ($app) {
            $driver = $app['config']->get('image.driver', 'gd');

            return match ($driver) {
                'imagick' => new ImageManager(new ImagickDriver),
                default => new ImageManager(new GdDriver),
            };
        });

        $this->app->alias('image_manager', ImageManager::class);
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
