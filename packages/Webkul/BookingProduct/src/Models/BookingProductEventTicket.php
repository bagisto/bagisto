<?php

namespace Webkul\BookingProduct\Models;

use Webkul\BookingProduct\Contracts\BookingProductEventTicket as BookingProductEventTicketContract;
use Webkul\Core\Eloquent\TranslatableModel;

class BookingProductEventTicket extends TranslatableModel implements BookingProductEventTicketContract
{
    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Summary of translatedAttributes
     */
    public $translatedAttributes = [
        'name',
        'description',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'price',
        'qty',
        'special_price',
        'special_price_from',
        'special_price_to',
        'booking_product_id',
    ];
}
