<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\ProductProxy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webkul\BookingProduct\Database\Factories\BookingProductFactory;
use Webkul\BookingProduct\Contracts\BookingProduct as BookingProductContract;

class BookingProduct extends Model implements BookingProductContract
{
    use HasFactory;

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

    protected $with = [
        'default_slot',
        'appointment_slot',
        'event_tickets',
        'rental_slot',
        'table_slot',
    ];

    protected $casts = [
        'available_from' => 'datetime',
        'available_to'   => 'datetime',
    ];

    /**
     * The Product Default Booking that belong to the product booking.
     */
    public function default_slot(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BookingProductDefaultSlotProxy::modelClass());
    }

    /**
     * The Product Appointment Booking that belong to the product booking.
     */
    public function appointment_slot(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BookingProductAppointmentSlotProxy::modelClass());
    }

    /**
     * The Product Event Booking that belong to the product booking.
     */
    public function event_tickets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BookingProductEventTicketProxy::modelClass());
    }

    /**
     * The Product Rental Booking that belong to the product booking.
     */
    public function rental_slot(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BookingProductRentalSlotProxy::modelClass());
    }

    /**
     * The Product Table Booking that belong to the product booking.
     */
    public function table_slot(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BookingProductTableSlotProxy::modelClass());
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return BookingProductFactory::new();
    }

    /**
     * The Product belong to the product booking.
     */
    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }
}