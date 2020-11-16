<?php

namespace Webkul\BookingProduct\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
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
}