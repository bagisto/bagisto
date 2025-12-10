<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\RMA\Contracts\RMACustomField as RMACustomFieldContracts;

class RMACustomField extends Model implements RMACustomFieldContracts
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rma_custom_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'code',
        'label',
        'type',
        'is_required',
        'position',
        'input_validation',
    ];

    /**
     * Get the options for the custom field.
     */
    public function options(): HasMany
    {
        return $this->hasMany(RMACustomFieldOption::class, 'rma_custom_field_id');
    }
}
