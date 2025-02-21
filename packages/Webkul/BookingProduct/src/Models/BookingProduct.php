<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\BookingProduct\Contracts\BookingProduct as BookingProductContract;
use Webkul\Product\Models\ProductProxy;

class BookingProduct extends Model implements BookingProductContract
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'location',
        'show_location',
        'type',
        'qty',
        'available_every_week',
        'available_from',
        'available_to',
        'product_id',
    ];

    /**
     * The relations to eager load on every query.
     */
    protected $with = [
        'default_slot',
        'appointment_slot',
        'event_tickets',
        'rental_slot',
        'table_slot',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'available_from' => 'datetime',
        'available_to'   => 'datetime',
    ];

    /**
     * The Product Default Booking that belong to the product booking.
     */
    public function default_slot(): HasOne
    {
        return $this->hasOne(BookingProductDefaultSlotProxy::modelClass());
    }

    /**
     * The Product Appointment Booking that belong to the product booking.
     */
    public function appointment_slot(): HasOne
    {
        return $this->hasOne(BookingProductAppointmentSlotProxy::modelClass());
    }

    /**
     * The Product Event Booking that belong to the product booking.
     */
    public function event_tickets(): HasMany
    {
        return $this->hasMany(BookingProductEventTicketProxy::modelClass());
    }

    /**
     * The Product Rental Booking that belong to the product booking.
     */
    public function rental_slot(): HasOne
    {
        return $this->hasOne(BookingProductRentalSlotProxy::modelClass());
    }

    /**
     * The Product Table Booking that belong to the product booking.
     */
    public function table_slot(): HasOne
    {
        return $this->hasOne(BookingProductTableSlotProxy::modelClass());
    }

    /**
     * The Product belong to the product booking.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}
