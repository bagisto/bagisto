<?php

namespace Brainstream\Giftcard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCardBalance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'giftcard_number',
        'giftcard_amount',
        'used_giftcard_amount',
        'remaining_giftcard_amount'
    ];

    public function giftCard()
    {
        return $this->belongsTo(GiftCard::class, 'giftcard_number');
    }

}
