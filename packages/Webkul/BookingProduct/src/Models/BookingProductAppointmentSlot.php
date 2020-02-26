<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\BookingProduct\Contracts\BookingProductAppointmentSlot as BookingProductAppointmentSlotContract;

class BookingProductAppointmentSlot extends Model implements BookingProductAppointmentSlotContract
{
    public $timestamps = false;
    
    protected $casts = ['slots' => 'array'];

    protected $fillable = ['duration', 'break_time', 'same_slot_all_days', 'slots', 'booking_product_id'];
}