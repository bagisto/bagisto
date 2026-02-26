<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\BookingProduct\Contracts\BookingProductRentalSlot as BookingProductRentalSlotContract;

class BookingProductRentalSlot extends Model implements BookingProductRentalSlotContract
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
        'renting_type',
        'daily_price',
        'hourly_price',
        'same_slot_all_days',
        'slots',
        'booking_product_id',
    ];
}
