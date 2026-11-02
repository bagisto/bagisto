<?php

namespace Webkul\ImageCache\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\ServiceProvider;
use Webkul\ImageCache\Exceptions\InvalidTemplate;
use Webkul\ImageCache\Exceptions\UnprocessableImage;
use Webkul\ImageCache\Exceptions\UnreadableImage;
use Webkul\ImageCache\Http\Controllers\ImageCacheController;
use Webkul\ImageCache\ImageUrlBuilder;
use Webkul\ImageCache\TemplateRegistry;

class ImageCacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        include __DIR__.'/../Http/helpers.php';

        $this->mergeConfigFrom(__DIR__.'/../Config/imagecache.php', 'imagecache');

        $this->app->scopedIf(TemplateRegistry::class);

        $this->throttleReports();
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
        $route = ImageUrlBuilder::route();

        if (! is_null($route)) {
            $filenamePattern = '[ \w\\.\\/\\-\\@\(\)\=]+';

            $this->app['router']
                ->get($route.'/{template}/{filename}', [ImageCacheController::class, 'getResponse'])
                ->name('imagecache')
                ->where(['filename' => $filenamePattern]);
        }
    }

    /**
     * Report each image cache fault once an hour, since every page and image that meets a broken
     * template, image or disk would otherwise report it again.
     */
    protected function throttleReports(): void
    {
        $this->callAfterResolving(Handler::class, function (Handler $handler) {
            $handler->throttleUsing(fn (InvalidTemplate $exception) => Limit::perHour(1)->by($exception->getMessage()));

            $handler->throttleUsing(fn (UnprocessableImage $exception) => Limit::perHour(1)->by($exception->throttleKey()));

            $handler->throttleUsing(fn (UnreadableImage $exception) => Limit::perHour(1)->by($exception->throttleKey()));
        });
    }
}
