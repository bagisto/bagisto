<?php

namespace Webkul\Shop\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Core\Tree;
use Webkul\Shop\Http\Middleware\AuthenticateCustomer;
use Webkul\Shop\Http\Middleware\Currency;
use Webkul\Shop\Http\Middleware\Locale;
use Webkul\Shop\Http\Middleware\Theme;
use Webkul\Shop\Http\Resources\CategoryTreeResource;

class ShopServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(Router $router): void
    {
        \URL::forcescheme('https');

        /* loaders */
        Route::middleware('web')->group(__DIR__.'/../Routes/web.php');
        Route::middleware('web')->group(__DIR__.'/../Routes/api.php');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'shop');
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'shop');

        /* aliases */
        $router->aliasMiddleware('currency', Currency::class);
        $router->aliasMiddleware('locale', Locale::class);
        $router->aliasMiddleware('customer', AuthenticateCustomer::class);
        $router->aliasMiddleware('theme', Theme::class);

//        $this->publishes([
//            dirname(__DIR__).'/Config/imagecache.php' => config_path('imagecache.php'),
//        ]);



        /* Paginator */
        Paginator::defaultView('shop::partials.pagination');
        Paginator::defaultSimpleView('shop::partials.pagination');

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'shop');

        /* View Composers */
        $this->composeView();

        View::share(
            'sharedCategories',
            resolve(CategoryRepository::class)
                ->getVisibleCategoryTree(core()->getCurrentChannel()->root_category_id)
                ->toArray()
        );


        /* Breadcrumbs */
        require __DIR__.'/../Routes/breadcrumbs.php';

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Bind the the data to the views.
     *
     * @return void
     */
    protected function composeView()
    {
        view()->composer('shop::customers.account.partials.sidemenu', function ($view) {
            $tree = Tree::create();

            foreach (config('menu.customer') as $item) {
                $tree->add($item, 'menu');
            }

            $tree->items = core()->sortItems($tree->items);

            $view->with('menu', $tree);
        });

        foreach (core()->getAllChannels() as $channel) {
            foreach (core()->getAllLocales() as $locale) {
                $key = $locale->code.'_visible_category_tree_'.$channel->id;
                view()->share($key, cache()->remember(
                    $key,
                    10 * 60,
                    fn () => resolve(CategoryRepository::class)->getVisibleCategoryTree($channel->id)
                ));
            }
        }
    }

    /**
     * Register package config.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/menu.php',
            'menu.customer'
        );
    }
}
