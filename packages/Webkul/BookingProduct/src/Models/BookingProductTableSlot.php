<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\BookingProduct\Contracts\BookingProductTableSlot as BookingProductTableSlotContract;

class BookingProductTableSlot extends Model implements BookingProductTableSlotContract
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
        'price_type',
        'guest_limit',
        'duration',
        'break_time',
        'prevent_scheduling_before',
        'same_slot_all_days',
        'slots',
        'booking_product_id',
    ];
}
