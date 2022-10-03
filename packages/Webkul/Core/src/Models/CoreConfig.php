<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Contracts\CoreConfig as CoreConfigContract;

class CoreConfig extends Model implements CoreConfigContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'core_config';

    /**
     * Fillable for mass assignment
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'value',
        'channel_code',
        'locale_code',
    ];

    /**
     * Hidden properties
     *
     * @var array
     */
    protected $hidden = ['token'];
}