<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\Admin\Database\Factories\CurrencyExchangeRateFactory;
use Webkul\Core\Contracts\CurrencyExchangeRate as CurrencyExchangeRateContract;

class CurrencyExchangeRate extends Model implements CurrencyExchangeRateContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'target_currency',
        'rate',
    ];

    /**
     * Get the exchange rate associated with the currency.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(CurrencyProxy::modelClass(), 'target_currency');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return CurrencyExchangeRateFactory::new();
    }
}
