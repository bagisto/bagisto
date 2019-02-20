<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Contracts\Slider as SliderContract;

class Slider extends Model implements SliderContract
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