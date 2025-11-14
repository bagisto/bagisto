<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\RMA\Contracts\ReasonResolution as ReasonResolutionContract;

class ReasonResolution extends Model implements ReasonResolutionContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rma_reason_resolutions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rma_reason_id',
        'resolution_type',
    ];
}