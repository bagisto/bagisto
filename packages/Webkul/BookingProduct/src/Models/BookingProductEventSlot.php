<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\BookingProduct\Contracts\BookingProductEventSlot as BookingProductEventSlotContract;

class BookingProductEventSlot extends Model implements BookingProductEventSlotContract
{
    public $timestamps = false;
    
    protected $casts = ['slots' => 'array'];

    protected $fillable = ['available_from', 'available_to', 'slots', 'booking_product_id'];
}