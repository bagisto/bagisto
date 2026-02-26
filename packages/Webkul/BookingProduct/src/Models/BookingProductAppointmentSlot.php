<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\BookingProduct\Contracts\BookingProductAppointmentSlot as BookingProductAppointmentSlotContract;

class BookingProductAppointmentSlot extends Model implements BookingProductAppointmentSlotContract
{
    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast.
     */
    protected $casts = ['slots' => 'array'];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'duration',
        'break_time',
        'same_slot_all_days',
        'slots',
        'booking_product_id',
    ];
}
