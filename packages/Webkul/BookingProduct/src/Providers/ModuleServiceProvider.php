<?php

namespace Webkul\BookingProduct\Providers;

use Webkul\BookingProduct\Models\Booking;
use Webkul\BookingProduct\Models\BookingProduct;
use Webkul\BookingProduct\Models\BookingProductAppointmentSlot;
use Webkul\BookingProduct\Models\BookingProductDefaultSlot;
use Webkul\BookingProduct\Models\BookingProductEventTicket;
use Webkul\BookingProduct\Models\BookingProductEventTicketTranslation;
use Webkul\BookingProduct\Models\BookingProductRentalSlot;
use Webkul\BookingProduct\Models\BookingProductTableSlot;
use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    /**
     * Models.
     *
     * @var array
     */
    protected $models = [
        BookingProduct::class,
        BookingProductDefaultSlot::class,
        BookingProductAppointmentSlot::class,
        BookingProductEventTicket::class,
        BookingProductEventTicketTranslation::class,
        BookingProductRentalSlot::class,
        BookingProductTableSlot::class,
        Booking::class,
    ];
}
