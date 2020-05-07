<?php

namespace Webkul\Bulkupload\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Bulkupload\Contracts\DataFlowProfile as DataFlowProfileContract;
use Webkul\Attribute\Models\AttributeFamily;


class DataFlowProfile extends Model implements DataFlowProfileContract
{
    protected $guarded = array();

    protected $table = "bulkupload_dataflowprofile";

    public function attribute_family()
    {
        return $this->hasOne(AttributeFamily::class, 'id', 'attribute_family_id');
    }
}
