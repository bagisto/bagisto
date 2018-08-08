<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'sliders';

    protected $fillable = [
        'title', 'path','content','channel_id'
    ];
}