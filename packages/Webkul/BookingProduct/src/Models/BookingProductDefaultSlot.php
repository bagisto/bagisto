<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\BookingProduct\Contracts\BookingProductDefaultSlot as BookingProductDefaultSlotContract;

class BookingProductDefaultSlot extends Model implements BookingProductDefaultSlotContract
{
    protected $table = 'booking_product_default_slots';

    public $timestamps = false;
    
    protected $casts = ['slots' => 'array'];

    protected $fillable = ['booking_type', 'duration', 'break_time', 'available_from', 'available_to', 'slots', 'booking_product_id'];

    /**
     * Get the product that owns the attribute value.
     */
    public function booking_product()
    {
        return $this->belongsTo(BookingProductProxy::modelClass());
    }
}