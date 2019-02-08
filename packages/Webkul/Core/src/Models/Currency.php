<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\CurrencyExchangeRate;
use Webkul\Core\Models\CurrencyCodeSymbol;

class Currency extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name'
    ];

    /**
     * Get the currency_exchange associated with the currency.
     */
    public function CurrencyExchangeRate()
    {
        return $this->hasOne(CurrencyExchangeRate::class, 'target_currency');
    }

    public function getCurrencySymbol() {
        return $this->hasOne(CurrencyCodeSymbol::class, 'code', 'code');
    }
}