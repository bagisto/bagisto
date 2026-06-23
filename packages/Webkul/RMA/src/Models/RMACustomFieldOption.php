<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webkul\RMA\Contracts\RMACustomFieldOption as ContractsRMACustomFieldOption;

class RMACustomFieldOption extends Model implements ContractsRMACustomFieldOption
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rma_custom_field_options';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rma_custom_field_id',
        'name',
        'value',
    ];

    /**
     * Get the additional rma field that owns the option.
     */
    public function rmaCustomField(): BelongsTo
    {
        return $this->belongsTo(RMACustomField::class, 'rma_custom_field_id');
    }
}
