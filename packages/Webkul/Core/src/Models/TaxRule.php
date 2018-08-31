<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;

class TaxRule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'tax_rules';

    protected $fillable = [
        'code', 'name' ,'description'
    ];
}