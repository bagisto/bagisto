<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\BookingProduct\Contracts\BookingProductRentalSlot as BookingProductRentalSlotContract;

class BookingProductRentalSlot extends Model implements BookingProductRentalSlotContract
{
    public $timestamps = false;
    
    protected $casts = ['slots' => 'array'];

    protected $fillable = ['renting_type', 'daily_price', 'hourly_price', 'available_every_week', 'slot_has_quantity', 'same_slot_all_days', 'slots', 'booking_product_id'];
}