<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyExchangeRate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'target_currency', 'rate'
    ];
}