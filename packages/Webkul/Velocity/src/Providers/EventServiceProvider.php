<?php

namespace Webkul\Velocity\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen([
            'bagisto.admin.settings.locale.edit.after',
            'bagisto.admin.settings.locale.create.after',
        ], function($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate(
                    'velocity::admin.settings.locales.locale-logo'
                );
            }
        );

        Event::listen([
            'bagisto.admin.catalog.category.edit_form_accordian.description_images.controls.after',
            'bagisto.admin.catalog.category.create_form_accordian.description_images.controls.after',
        ], function($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate(
                    'velocity::admin.catelog.categories.category-icon'
                );
            }
        );

        Event::listen([
            'bagisto.admin.settings.slider.edit.after',
            'bagisto.admin.settings.slider.create.after',
        ], function($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate(
                    'velocity::admin.settings.sliders.velocity-slider'
                );
            }
        );

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('velocity::admin.layouts.style');
        });

        Event::listen([
            'core.locale.create.after',
            'core.locale.update.after',
        ], 'Webkul\Velocity\Helpers\AdminHelper@saveLocaleImg');

        Event::listen([
            'catalog.category.create.after',
            'catalog.category.update.after',
        ], 'Webkul\Velocity\Helpers\AdminHelper@storeCategoryIcon');

        Event::listen([
            'core.settings.slider.create.after',
            'core.settings.slider.update.after',
        ], 'Webkul\Velocity\Helpers\AdminHelper@storeSliderDetails');

        Event::listen('checkout.order.save.after', 'Webkul\Velocity\Helpers\Helper@topBrand');
    }
}
