<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\BookingProduct\Contracts\BookingProductTableSlot as BookingProductTableSlotContract;

class BookingProductTableSlot extends Model implements BookingProductTableSlotContract
{
    public $timestamps = false;
    
    protected $casts = ['slots' => 'array'];

    protected $fillable = [
        'price_type',
        'guest_limit',
        'guest_capacity',
        'duration',
        'break_time',
        'prevent_scheduling_before',
        'same_slot_all_days',
        'slots',
        'booking_product_id',
    ];
}