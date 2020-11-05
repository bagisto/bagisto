<?php

namespace Webkul\BookingProduct\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\BookingProduct\Models\BookingProduct::class,
        \Webkul\BookingProduct\Models\BookingProductDefaultSlot::class,
        \Webkul\BookingProduct\Models\BookingProductAppointmentSlot::class,
        \Webkul\BookingProduct\Models\BookingProductEventTicket::class,
        \Webkul\BookingProduct\Models\BookingProductEventTicketTranslation::class,
        \Webkul\BookingProduct\Models\BookingProductRentalSlot::class,
        \Webkul\BookingProduct\Models\BookingProductTableSlot::class,
        \Webkul\BookingProduct\Models\Booking::class,
    ];

    public function boot()
    {
        if ($this->areMigrationsEnabled()) {
            $this->registerMigrations();
        }

        if ($this->areModelsEnabled()) {
            $this->registerModels();
            $this->registerEnums();
            $this->registerRequestTypes();
        }

        if ($this->areViewsEnabled()) {
            $this->registerViews();
        }

        if ($routes = $this->config('routes', true)) {
            $this->registerRoutes($routes);
        }
    }
}