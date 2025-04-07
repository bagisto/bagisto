<?php

namespace Brainstream\Giftcard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCard extends Model
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
        'giftcard_status',
        'creationdate',
        'expirationdate',
        'expirein',
        'sendername',
        'senderemail',
        'recipientname',
        'recipientemail',
        'message',
    ];

    protected $dates = ['expirationdate'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($giftCard) {
            
            // Assuming the initial amount used is 0
            $initialBalance = $giftCard->giftcard_amount;
            GiftCardBalance::create([
                'giftcard_number' => $giftCard->giftcard_number,
                'giftcard_amount' => $initialBalance,
                'used_giftcard_amount' => 0.00,
                'remaining_giftcard_amount' => $initialBalance,
            ]);
        });
    }

}
