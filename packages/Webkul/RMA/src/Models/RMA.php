<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\RMA\Contracts\RMA as RMAContract;
use Webkul\Sales\Models\OrderProxy;

class RMA extends Model implements RMAContract
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rma';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'information',
        'order_status',
        'rma_status_id',
        'order_id',
        'status',
        'package_condition',
    ];

    /**
     * RMA Status
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(RMAStatusProxy::modelClass(), 'rma_status_id');
    }

    /**
     * Define a one-to-many relationship with the RMA images.
     */
    public function images(): HasMany
    {
        return $this->hasMany(RMAImageProxy::modelClass(), 'rma_id');
    }

    /**
     * Define a one-to-many relationship with the RMA items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(RMAItemProxy::modelClass(), 'rma_id');
    }

    /**
     * Define a one-to-one relationship with the RMA.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderProxy::modelClass(), 'order_id');
    }

    /**
     * Define a one-to-many relationship with the RMA items.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(RMAMessageProxy::modelClass(), 'rma_id');
    }

    /**
     * Define a one-to-many relationship with the RMA items.
     */
    public function additionalFields(): HasMany
    {
        return $this->hasMany(RMAAdditionalFieldProxy::modelClass(), 'rma_id');
    }
}