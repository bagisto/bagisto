<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;

class TaxMap extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'tax_map';

    protected $fillable = [
        'channel_id', 'tax_rule', 'tax_rate'
    ];
}