<?php

namespace Webkul\BookingProduct\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\BookingProduct\Contracts\BookingProductDefaultSlot as BookingProductDefaultSlotContract;

class BookingProductDefaultSlot extends Model implements BookingProductDefaultSlotContract
{
    /**
     * The table associated with the model.
     */
    protected $table = 'booking_product_default_slots';

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
        'booking_type',
        'duration',
        'break_time',
        'slots',
        'booking_product_id',
    ];

    /**
     * Get the product that owns the attribute value.
     */
    public function booking_product()
    {
        return $this->belongsTo(BookingProductProxy::modelClass());
    }
}
