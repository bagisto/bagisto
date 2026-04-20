<?php

namespace Webkul\Omnibus\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Webkul\Omnibus\Listeners\ProductPriceChange;
use Webkul\Omnibus\Services\OmnibusPriceManager;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'catalog.product.create.after' => [
            ProductPriceChange::class,
        ],

        'catalog.product.update.after' => [
            ProductPriceChange::class,
        ],
    ];

    /**
     * Register any other events for the application.
     */
    public function boot(): void
    {
        parent::boot();

        Event::listen('bagisto.shop.products.price.after', function ($viewRenderEventManager) {
            if (! app(OmnibusPriceManager::class)->isEnabled()) {
                return;
            }

            $viewRenderEventManager->addTemplate('omnibus::shop.inject');
        });
    }
}
