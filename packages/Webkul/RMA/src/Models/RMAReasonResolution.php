<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\RMA\Contracts\RMAReasonResolution as RMAReasonResolutionContract;

class RMAReasonResolution extends Model implements RMAReasonResolutionContract
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
