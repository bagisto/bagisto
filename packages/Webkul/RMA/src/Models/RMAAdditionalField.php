<?php

namespace Webkul\RMA\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\RMA\Contracts\RMAAdditionalField as RMAAdditionalFieldContracts;

class RMAAdditionalField extends Model implements RMAAdditionalFieldContracts
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rma_additional_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rma_id',
        'name',
        'value',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public function customField()
    {
        return $this->belongsTo(RMACustomFieldProxy::modelClass(), 'field_name', 'code');
    }
}
