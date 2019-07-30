<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Contracts\Locale as LocaleContract;

class Locale extends Model implements LocaleContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'name', 'direction'
    ];
}
