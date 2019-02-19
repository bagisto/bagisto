<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Contracts\Currency as CurrencyContract;

class Currency extends Model implements CurrencyContract
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
        return $this->hasOne(CurrencyExchangeRateProxy::modelClass(), 'target_currency');
    }
}