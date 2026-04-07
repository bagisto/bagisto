<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\Core\Contracts\Currency as CurrencyContract;
use Webkul\Core\Database\Factories\CurrencyFactory;

class Currency extends Model implements CurrencyContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'decimal',
        'group_separator',
        'decimal_separator',
        'currency_position',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'decimal' => 'integer',
    ];

    /**
     * Set currency code in capital letter.
     */
    public function setCodeAttribute($code): void
    {
        $this->attributes['code'] = strtoupper($code);
    }

    /**
     * Set decimal digits with fallback to default.
     */
    public function setDecimalAttribute($value): void
    {
        $this->attributes['decimal'] = $value !== '' && $value !== null ? (int) $value : 2;
    }

    /**
     * Get the exchange rate associated with the currency.
     */
    public function exchange_rate(): HasOne
    {
        return $this->hasOne(CurrencyExchangeRateProxy::modelClass(), 'target_currency');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return CurrencyFactory::new();
    }
}
