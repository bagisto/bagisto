<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\RMA\Contracts\RMAReason as RMAReasonContract;

class RMAReason extends Model implements RMAReasonContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rma_reasons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'status',
        'position',
    ];

    /**
     * Define has many relationship with rma resolution.
     */
    public function reasonResolutions(): HasMany
    {
        return $this->hasMany(RMAReasonResolution::class, 'rma_reason_id');
    }
}
