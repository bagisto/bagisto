<?php

namespace Webkul\Attribute\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Attribute\Models\Attribute;

class AttributeGroup extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['name', 'position'];

    /**
     * Get the attributes that owns the attribute group.
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_group_mappings');
    }
}