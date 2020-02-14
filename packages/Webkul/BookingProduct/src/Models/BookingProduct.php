<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\ProductProxy;
use Webkul\BookingProduct\Contracts\BookingProduct as BookingProductContract;

class BookingProduct extends Model implements BookingProductContract
{
    protected $fillable = ['location', 'show_location', 'type', 'product_id'];

    protected $with = ['default_slot', 'appointment_slot', 'event_slot', 'rental_slot', 'table_slot'];

    /**
     * The Product Default Booking that belong to the product booking.
     */
    public function default_slot()
    {
        return $this->hasOne(BookingProductDefaultSlotProxy::modelClass());
    }

    /**
     * The Product Appointment Booking that belong to the product booking.
     */
    public function appointment_slot()
    {
        return $this->hasOne(BookingProductAppointmentSlotProxy::modelClass());
    }

    /**
     * The Product Event Booking that belong to the product booking.
     */
    public function event_slot()
    {
        return $this->hasOne(BookingProductEventSlotProxy::modelClass());
    }

    /**
     * The Product Rental Booking that belong to the product booking.
     */
    public function rental_slot()
    {
        return $this->hasOne(BookingProductRentalSlotProxy::modelClass());
    }

    /**
     * The Product Table Booking that belong to the product booking.
     */
    public function table_slot()
    {
        return $this->hasOne(BookingProductTableSlotProxy::modelClass());
    }
}