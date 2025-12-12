<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\RMA\Contracts\RMAImage as RMAImageContract;

class RMAImage extends Model implements RMAImageContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rma_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rma_id',
        'path',
    ];
}
