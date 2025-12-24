<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\RMA\Contracts\RMAStatus as RMAStatusContract;

class RMAStatus extends Model implements RMAStatusContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rma_statuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'status',
        'color',
    ];
}
